<?php
/*****************************
 *
 * RouterOS PHP API class v1.7
 * Author: Denis Basta
 * Contributors:
 *    Nick Barnes
 *    Ben Menking (ben [at] infotechsc [dot] com)
 *    Jeremy Jefferson (http://jeremyj.com)
 *    Cristian Deluxe (djcristiandeluxe [at] gmail [dot] com)
 *    Mikhail Moskalev (mmv.rus [at] gmail [dot] com)
 *
 * http://www.mikrotik.com
 * http://wiki.mikrotik.com/wiki/API_PHP_class
 *
 ******************************/

class RouterosAPI
{
	var $version_major = 1;
	var $version_minor = 7;
		
    var $debug     = false; //  Show debug information
    var $port      = 8728;  //  Port to connect to (default 8729 for ssl)
    var $ssl       = false; //  Connect using SSL (must enable api-ssl in IP/Services)
    var $timeout   = 3;     //  Connection attempt timeout and data read timeout

    var $socket;            //  Variable for storing socket resource
    var $error_no;          //  Variable for storing connection error number, if any
    var $error_str;         //  Variable for storing connection error text, if any
		
	var $last_command;			// variable that contains last command sent to device
		
		/* deprecated variables - remaining for backwards compatibility */
    var $attempts  = 5;     //  Connection attempt count
    var $delay     = 3;     //  Delay between connection attempts in seconds
	var $connected = false; //  Connection state		

    /* Check, can be var used in foreach  */
    public function isIterable($var)
    {
        return $var !== null
                && (is_array($var)
                || $var instanceof Traversable
                || $var instanceof Iterator
                || $var instanceof IteratorAggregate
                );
    }

    /**
     * Print text for debug purposes
     *
     * @param string      $text       Text to print
     *
     * @return void
     */
    public function debug($text)
    {
        if ($this->debug) {
            echo $text . "\n";
        }
    }


    /**
     *
     *
     * @param string        $length
     *
     * @return void
     */
    public function encodeLength($length)
    {
        if ($length < 0x80) {
            $length = chr($length);
        } elseif ($length < 0x4000) {
            $length |= 0x8000;
            $length = chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length < 0x200000) {
            $length |= 0xC00000;
            $length = chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length < 0x10000000) {
            $length |= 0xE0000000;
            $length = chr(($length >> 24) & 0xFF) . chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        } elseif ($length >= 0x10000000) {
            $length = chr(0xF0) . chr(($length >> 24) & 0xFF) . chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF) . chr($length & 0xFF);
        }

        return $length;
    }


    /**
     * Login to RouterOS
     *
     * @param string      $ip         Hostname (IP or domain) of the RouterOS server
     * @param string      $login      The RouterOS username
     * @param string      $password   The RouterOS password
     *
     * @return boolean                If we are connected or not
     */
    public function connect($ip, $login, $password)
    {
				$this->socket = false;

        $PROTOCOL = ($this->ssl ? 'ssl://' : '' );
				if( $this->ssl ) {
						$context = stream_context_create(
								array('ssl' => array('ciphers' => 'ADH:ALL', 'verify_peer' => false, 'verify_peer_name' => false)));
						$PROTOCOL = 'ssl://';
				}
				else {
					$PROTOCOL = '';
					$context = stream_context_create();
				}
				
        $this->debug('Connection attempt to ' . $PROTOCOL . $ip . ':' . $this->port . '...');
				
        $this->socket = @stream_socket_client($PROTOCOL . $ip.':'. $this->port, $this->error_no, $this->error_str, $this->timeout, 
					STREAM_CLIENT_CONNECT, $context);
				
				if ($this->socket) {
						$this->debug("Socket connected and is valid");
						socket_set_timeout($this->socket, $this->timeout);
						$this->debug("Querying device with '/login'");
						
						$this->write('/login');
						$RESPONSE = $this->read(false);
						if (isset($RESPONSE[0]) && $RESPONSE[0] == '!done') {
								$MATCHES = array();
								if (preg_match_all('/[^=]+/i', $RESPONSE[1], $MATCHES)) {
										if ($MATCHES[0][0] == 'ret' && strlen($MATCHES[0][1]) == 32) {
												$this->debug("Device responded as expected");
												$this->write('/login', false);
												$this->write('=name=' . $login, false);
												$this->write('=response=00' . md5(chr(0) . $password . pack('H*', $MATCHES[0][1])));
												$RESPONSE = $this->read(false);
												if (isset($RESPONSE[0]) && $RESPONSE[0] == '!done') {
														$this->debug("Device authenticated us");
														$this->connected = true;
												}
												else {
													$this->error_no = -1;
													$this->error_str = "Failed to authenticate to device";
													fclose($this->socket);
												}
										}
										else {
											$this->error_no = -1;
											$this->error_str = "Matched response from device but data not correct: '{$RESPONSE[1]}'";
											fclose($this->socket);
										}
								}
								else {
									$this->error_no = -1;
									$this->error_str = "Response from device didn't match preg expectation: '{$RESPONSE[1]}'";
									fclose($this->socket);
								}
						}
				}

				$this->debug("Connected: " . ($this->connected?'true':'false'));

        return $this->connected;
    }


    /**
     * Disconnect from RouterOS
     *
     * @return void
     */
    public function disconnect()
    {
        // let's make sure this socket is still valid.  it may have been closed by something else
        if( is_resource($this->socket) ) {
            fclose($this->socket);
        }
        $this->connected = false;
    }


    /**
     * Parse response from Router OS
     *
     * @param array       $response   Response data
     *
     * @return array                  Array with parsed data
     */
    public function parseResponse($response)
    {
        if (is_array($response)) {
            $PARSED      = array();
            $CURRENT     = null;
            $singlevalue = null;
            foreach ($response as $x) {
                if (in_array($x, array('!fatal','!re','!trap'))) {
                    if ($x == '!re') {
                        $CURRENT =& $PARSED[];
                    } else {
                        $CURRENT =& $PARSED[$x][];
                    }
                } elseif ($x != '!done') {
                    $MATCHES = array();
                    if (preg_match_all('/[^=]+/i', $x, $MATCHES)) {
                        if ($MATCHES[0][0] == 'ret') {
                            $singlevalue = $MATCHES[0][1];
                        }
                        $MATCHES_LEN[0]=count($MATCHES[0]);
                        if ($MATCHES_LEN[0]>2) {
                            $MATCHES_VALUE=$MATCHES[0];
                            array_shift($MATCHES_VALUE);
                            $MATCHES[0][1]=implode('=', $MATCHES_VALUE);
                            $MATCHES[0]=array_slice($MATCHES[0],0,2);
                        }
                        $CURRENT[$MATCHES[0][0]] = (isset($MATCHES[0][1]) ? $MATCHES[0][1] : '');
                    }
                }
            }

            if (empty($PARSED) && !is_null($singlevalue)) {
                $PARSED = $singlevalue;
            }

            return $PARSED;
        } else {
            return array();
        }
    }


    /**
     * Parse response from Router OS
     *
     * @param array       $response   Response data
     *
     * @return array                  Array with parsed data
     */
    public function parseResponse4Smarty($response)
    {
        if (is_array($response)) {
            $PARSED      = array();
            $CURRENT     = null;
            $singlevalue = null;
            foreach ($response as $x) {
                if (in_array($x, array('!fatal','!re','!trap'))) {
                    if ($x == '!re') {
                        $CURRENT =& $PARSED[];
                    } else {
                        $CURRENT =& $PARSED[$x][];
                    }
                } elseif ($x != '!done') {
                    $MATCHES = array();
                    if (preg_match_all('/[^=]+/i', $x, $MATCHES)) {
                        if ($MATCHES[0][0] == 'ret') {
                            $singlevalue = $MATCHES[0][1];
                        }
                        $CURRENT[$MATCHES[0][0]] = (isset($MATCHES[0][1]) ? $MATCHES[0][1] : '');
                    }
                }
            }
            foreach ($PARSED as $key => $value) {
                $PARSED[$key] = $this->arrayChangeKeyName($value);
            }
            return $PARSED;
            if (empty($PARSED) && !is_null($singlevalue)) {
                $PARSED = $singlevalue;
            }
        } else {
            return array();
        }
    }


    /**
     * Change "-" and "/" from array key to "_"
     *
     * @param array       $array      Input array
     *
     * @return array                  Array with changed key names
     */
    public function arrayChangeKeyName(&$array)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $tmp = str_replace("-", "_", $k);
                $tmp = str_replace("/", "_", $tmp);
                if ($tmp) {
                    $array_new[$tmp] = $v;
                } else {
                    $array_new[$k] = $v;
                }
            }
            return $array_new;
        } else {
            return $array;
        }
    }

		private function check_socket() {
				$check = is_resource($this->socket);
				
				if( !$check ) {
						$this->error_no = socket_last_error();
						$this->error_str = socket_strerror($this->error_no);
						$this->connected = false;
				}
				
				return $check;
		}

    /**
     * Read data from Router OS
     *
     * @param boolean     $parse      Parse the data? default: true
     *
     * @return array                  Array with parsed or unparsed data
     */
    public function read($parse = true)
    {
        $RESPONSE     = array();
        $receiveddone = false;
        while ($this->check_socket()) {
            // Read the first byte of input which gives us some or all of the length
            // of the remaining reply.
            $BYTE   = ord(fread($this->socket, 1));
            $LENGTH = 0;
            // If the first bit is set then we need to remove the first four bits, shift left 8
            // and then read another byte in.
            // We repeat this for the second and third bits.
            // If the fourth bit is set, we need to remove anything left in the first byte
            // and then read in yet another byte.
            if ($BYTE & 128) {
                if (($BYTE & 192) == 128) {
                    $LENGTH = (($BYTE & 63) << 8) + ord(fread($this->socket, 1));
                } else {
                    if (($BYTE & 224) == 192) {
                        $LENGTH = (($BYTE & 31) << 8) + ord(fread($this->socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                    } else {
                        if (($BYTE & 240) == 224) {
                            $LENGTH = (($BYTE & 15) << 8) + ord(fread($this->socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        } else {
                            $LENGTH = ord(fread($this->socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($this->socket, 1));
                        }
                    }
                }
            } else {
                $LENGTH = $BYTE;
            }

            $_ = "";

            // If we have got more characters to read, read them in.
            if ($LENGTH > 0) {
                $_      = "";
                $retlen = 0;
                while ($retlen < $LENGTH) {
                    $toread = $LENGTH - $retlen;
                    $_ .= fread($this->socket, $toread);
                    $retlen = strlen($_);
                }
                $RESPONSE[] = $_;
                $this->debug('>>> [' . $retlen . '/' . $LENGTH . '] bytes read.');
            }

            // If we get a !done, make a note of it.
            if ($_ == "!done") {
                $receiveddone = true;
            }

            $STATUS = socket_get_status($this->socket);
            if ($LENGTH > 0) {
                $this->debug('>>> [' . $LENGTH . ', ' . $STATUS['unread_bytes'] . ']' . $_);
            }

            if ((!$this->connected && !$STATUS['unread_bytes']) || ($this->connected && !$STATUS['unread_bytes'] && $receiveddone)) {
                break;
            }
        }

				if( !$this->check_socket() ) {
						return false;
				}
				
        if ($parse) {
            $RESPONSE = $this->parseResponse($RESPONSE);
        }

        return $RESPONSE;
    }


    /**
     * Write (send) data to Router OS
     *
     * @param string      $command    A string with the command to send
     * @param mixed       $param2     If we set an integer, the command will send this data as a "tag"
     *                                If we set it to boolean true, the funcion will send the comand and finish
     *                                If we set it to boolean false, the funcion will send the comand and wait for next command
     *                                Default: true
     *
     * @return boolean                Return false if no command especified
     */
    public function write($command, $param2 = true)
    {
				if( !$this->check_socket() ) {
					return false;
				}
				
        if ($command) {
					$this->last_command = $command;
					
            $data = explode("\n", $command);
            foreach ($data as $com) {
                $com = trim($com);
                fwrite($this->socket, $this->encodeLength(strlen($com)) . $com);
                $this->debug('<<< [' . strlen($com) . '] ' . $com);
            }

            if (gettype($param2) == 'integer') {
                fwrite($this->socket, $this->encodeLength(strlen('.tag=' . $param2)) . '.tag=' . $param2 . chr(0));
                $this->debug('<<< [' . strlen('.tag=' . $param2) . '] .tag=' . $param2);
            } elseif (gettype($param2) == 'boolean') {
                fwrite($this->socket, ($param2 ? chr(0) : ''));
            }

            return true;
        } else {
            return false;
        }
    }


    /**
     * Write (send) request to Router OS and wait until command has completed
     *
     * @param string      $com        A string with the command to send
     * @param array       $arr        An array with arguments or queries
     *
     * @return array                  Array with parsed
     */
    public function comm($com, $arr = array())
    {
        $count = count($arr);
        $this->write($com, !$arr);
        $i = 0;
        if ($this->isIterable($arr)) {
            foreach ($arr as $k => $v) {
                switch ($k[0]) {
                    case "?":
                        $el = "$k=$v";
                        break;
                    case "~":
                        $el = "$k~$v";
                        break;
                    default:
                        $el = "=$k=$v";
                        break;
                }

                $last = ($i++ == $count - 1);
                $this->write($el, $last);
            }
        }

				// special case.  tool fetch never returns !done, so the command never completes.  we need to check
				// for that
				//if( $this->last_command == '/tool/fetch' ) {
				//	$response = 
				//}
				//else {
					return $this->read();
				//}
    }

    /**
     * Standard destructor
     *
     * @return void
     */
    public function __destruct()
    {
        $this->disconnect();
    }
}
