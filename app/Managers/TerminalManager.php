<?php
/**
 * TerminalManager
 * User agent string parsing class
 */
namespace App\Managers;

class TerminalManager
{
    /**
     * The carrier number of device.
     *
     * @var string
     */
    private $_carrier = null;

    /**
     * The carrier name of device.
     *
     * @var string
     */
    private $_carrier_name = null;

    /**
     * The device type of device.
     *
     * @var string
     */
    private $_device_type = null;

    /**
     * The device type name of device.
     *
     * @var string
     */
    private $_device_type_name = null;

    /**
     * The user agent of device.
     *
     * @var string
     */
    private $_user_agent = null;

    /**
     * Constants of devices type.
     */
    const IS_PC = 1;
    const IS_MB = 2;
    const IS_SP = 3;
    const UNKNOWN_DEVICE = 99;

    /**
     * Constants of device type name.
     */
    const IS_PC_TXT = 'pc';
    const IS_MB_TXT = 'mb';
    const IS_SP_TXT = 'sp';
    const UNKNOWN_DEVICE_TXT = '不明';

    /**
     * Constants of carrier.
     */
    const IS_DOCOMO   = 1;
    const IS_AU       = 2;
    const IS_SOFTBANK = 3;
    const IS_EMOBILE  = 4;
    const IS_OTHER    = 90;
    const UNKNOWN_CARRIER = 99;

    /**
     * Constants of carrier name.
     */
    const IS_DOCOMO_TXT   = 'docomo';
    const IS_AU_TXT       = 'au';
    const IS_SOFTBANK_TXT = 'softbank';
    const IS_EMOBILE_TXT  = 'emobile';
    const IS_OTHER_TXT    = 'その他';
    const UNKNOWN_CARRIER_TXT = '不明';

    /**
     * constructor
     * @param string $userAgent
     * @return boolean
     */
    public function __construct($userAgent = null)
    {
        if (!empty($userAgent)) {
            $this->_user_agent = $userAgent;

            /**
             * Set the carrier of a device.
             */
            if ($this->isDoCoMo($userAgent)) {
                $this->_carrier = self::IS_DOCOMO;
                $this->_carrier_name = self::IS_DOCOMO_TXT;
            } elseif ($this->isAU($userAgent)) {
                $this->_carrier = self::IS_AU;
                $this->_carrier_name = self::IS_AU_TXT;
            } elseif ($this->isSoftBank($userAgent)) {
                $this->_carrier = self::IS_SOFTBANK;
                $this->_carrier_name = self::IS_SOFTBANK_TXT;
            } elseif ($this->isEmobile($userAgent)) {
                $this->_carrier = self::IS_EMOBILE;
                $this->_carrier_name = self::IS_EMOBILE_TXT;
            } else {
                $this->_carrier = self::IS_OTHER;
                $this->_carrier_name = self::IS_OTHER_TXT;
            }

            /**
             * Set the device type of a device.
             */
            if ($this->isSmartPhone($userAgent)) {
                $this->_device_type = self::IS_SP;
                $this->_device_type_name = self::IS_SP_TXT;
            } elseif ($this->isMobile($userAgent)) {
                $this->_device_type = self::IS_MB;
                $this->_device_type_name = self::IS_MB_TXT;
            } else {
                $this->_device_type = self::IS_PC;
                $this->_device_type_name = self::IS_PC_TXT;
            }
        } else {
            $this->_carrier = self::UNKNOWN_CARRIER;
            $this->_carrier_name = self::UNKNOWN_CARRIER_TXT;
            $this->_device_type = self::UNKNOWN_DEVICE;
            $this->_device_type_name = self::UNKNOWN_DEVICE_TXT;
        }

        return true;
    }

    /**
     * Checks whether or not the user agent is mobile by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isMobile($userAgent = null)
    {
        if ($this->isSmartPhone($userAgent)) {
            return false;
        } elseif ($this->isIPad($userAgent)) {
            return false;
        } elseif ($this->isDoCoMo($userAgent)) {
            return true;
        } elseif ($this->isAU($userAgent)) {
            return true;
        } elseif ($this->isSoftBank($userAgent)) {
            return true;
        } elseif ($this->isEmobile($userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Docomo by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isDoCoMo($userAgent = null)
    {
        if (preg_match('/(docomo)/i', $userAgent)) {
            return true;
        } elseif (preg_match('/(FOMA)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is AU by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isAU($userAgent = null)
    {
        if (preg_match('/(KDDI)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Softbank by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isSoftBank($userAgent = null)
    {
        if (preg_match('/(Vodafone)/i', $userAgent)) {
            return true;
        } elseif (preg_match('/(J\-PHONE)/i', $userAgent)) {
            return true;
        } elseif (preg_match('/(softbank)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Emobile by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isEmobile($userAgent = null)
    {
        if (preg_match('/(emobile)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Smartphone by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isSmartPhone($userAgent = null)
    {
        if ($this->isIPhone($userAgent)) {
            return true;
        } elseif ($this->isAndroid($userAgent)) {
            return true;
        } elseif ($this->isWindowsPhone($userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is iPhone by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isIPhone($userAgent = null)
    {
        if (preg_match('/(iphone)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is iPad by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    private function isIPad($userAgent = null)
    {
        if (preg_match('/(ipad)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Android by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isAndroid($userAgent = null)
    {
        if (preg_match('/(android)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether or not the user agent is Windows Phone by user agent string.
     *
     * @param string $userAgent
     * @return boolean
     */
    public function isWindowsPhone($userAgent = null)
    {
        if (preg_match('/(windows phone)/i', $userAgent)) {
            return true;
        }

        return false;
    }

    /**
     * Gets the carrier of a device.
     *
     * @return string
     */
    public function getCarrier()
    {
        return $this->_carrier;
    }

    /**
     * Gets the carrier name of a device.
     *
     * @return string
     */
    public function getCarrierName()
    {
        return $this->_carrier_name;
    }

    /**
     * Gets the device type of a device.
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->_device_type;
    }

    /**
     * Gets the device name of a device.
     *
     * @return string
     */
    public function getDeviceTypeName()
    {
        return $this->_device_type_name;
    }

    /**
     * Gets User-Agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        return $userAgent;
    }
}
