<?php

namespace ByTIC\Payments\Mobilpay\FileLoader;

use ByTIC\Payments\Models\Methods\Traits\RecordTrait as PaymentMethodRecord;
use Nip\Filesystem\Filesystem;

/**
 * Trait HasFileLoader
 * @package ByTIC\Payments\Mobilpay\FileLoader
 *
 * @method PaymentMethodRecord getPaymentMethod
 */
trait HasFileLoader
{
    /**
     * @inheritDoc
     */
    public function initialize(array $parameters = [])
    {
        $this->initParamsForKeys($parameters);

        return parent::initialize($parameters);
    }

    /**
     * @param $parameters
     */
    protected function initParamsForKeys(&$parameters)
    {
        if (isset($parameters['private-key'])) {
            $parameters['privateKey'] = $parameters['private-key'];
            unset($parameters['private-key']);
        }

        if (isset($parameters['file']) && is_array($parameters['file'])) {
            $parameters['file'] = 'public.cer';
        }

        if (isset($parameters['privateKey']) && is_array($parameters['privateKey'])) {
            $parameters['privateKey'] = 'private.key';
        }
    }

    /**
     * @param $certificate
     * @throws \Exception
     */
    public function setFile($certificate)
    {
        if ($certificate == 'public.cer') {
            $certificate = $this->loadFileIntoModel('certificate');
        }

        return parent::setCertificate($certificate);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setPrivateKey($key)
    {
        if ($key == 'private.key') {
            $key = $this->loadFileIntoModel('privateKey');
        }

        return parent::setPrivateKey($key);
    }

    /**
     * @param $type
     * @return false|string
     */
    protected function loadFileIntoModel($type)
    {
        $filename = $this->getFilePath($type);
        $filesystem = new Filesystem();
        if (!file_exists($filename)) {
            return $filename;
        }

        $options = $this->getPaymentMethod()->getPaymentGatewayOptions();
        $content = file_get_contents($filename);
        $options[$type] = $content;
        if ($type == 'certificate') {
            unset($options['file']);
        } elseif ($type == 'privateKey') {
            unset($options['private-key']);
        }
        $this->getPaymentMethod()->setPaymentGatewayOptions($options);
        $this->getPaymentMethod()->save();

        $filesystem->remove($filename);
        @rmdir($this->getFileDirectoryPath());
        return $content;
    }

    /**
     * @param $type
     * @return bool|string
     * @throws \Exception
     */
    protected function validateFilePath($type)
    {
        $path = $this->getParameter($type);
        if (strpos($path, DIRECTORY_SEPARATOR) === false) {
            $path = $this->getFilePath($type);
        }

        return $this->setParameter($type, $path);
    }

    /**
     * @param $type
     * @return string
     */
    protected function getFilePath($type)
    {
        $fileName = [
            'certificate' => 'public.cer',
            'privateKey' => 'private.key',
        ];

        return $this->getFileDirectoryPath() . $fileName[$type];
    }

    /**
     * @return string
     */
    protected function getFileDirectoryPath()
    {
        return $this->getPaymentMethod() ?
            $this->getPaymentMethod()->getMediaRepository()->getCollection('files')->getBasePathForMedia()
            : null;
    }
}
