<?php

namespace ByTIC\Payments\Mobilpay;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Form as AbstractForm;
use ByTIC\Payments\Models\Methods\Types\CreditCards;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Form
 * @package ByTIC\Payments\Mobilpay
 */
class Form extends AbstractForm
{
    public function initElements()
    {
        parent::initElements();
        $this->initElementSandbox();
        $this->addInput('signature', 'Signature', false);
        $this->addInput('sellerId', 'Seller ID', false);

        $this->addFile('file', 'Certificate', false);
        $this->addTextarea('certificate', 'Certificate', false);
        $element = $this->getForm()->getElement('mobilpay[certificate]');
        $element->setAttrib('readonly', 'readonly');

        $this->addFile('private-key', 'Private key', false);
        $this->addTextarea('privateKey', 'Private key', false);
        $element = $this->getForm()->getElement('mobilpay[privateKey]');
        $element->setAttrib('readonly', 'readonly');
    }

    public function getDataFromModel()
    {
        $type = $this->getForm()->getModel()->getType();
        if ($type instanceof CreditCards) {
            $type->getGateway();
        }
        parent::getDataFromModel();
    }

    /**
     * @return bool
     */
    public function processValidation()
    {
        parent::processValidation();

        if (isset($_FILES['mobilpay'])) {
            $files = ['file', 'private-key'];
            foreach ($files as $fileType) {
                $element = $this->getForm()->getElement('mobilpay['.$fileType.']');

                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $element->getValue();
                if ($uploadedFile instanceof UploadedFile) {
                    if ($uploadedFile->isValid()) {
                        $extension = $fileType == 'file' ? 'cer' : 'key';
                        if ($uploadedFile->getClientOriginalExtension() != $extension) {
                            $element->addError(
                                'Invalid extension ['.$extension.']['.$uploadedFile->getClientOriginalName().']'
                            );
                        }
                    } else {
                        $element->addError($uploadedFile->getErrorMessage());
                    }
                }
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function generateModelOptions()
    {
        $options = parent::generateModelOptions();

        $files = ['certificate', 'privateKey'];

        return $this->files[$type];
    }

    /**
     * @return array
     */
    public function getOptionsForSaveToModel()
    {
        $options = parent::getOptionsForSaveToModel();
        unset($options['file'], $options['private-key']);
        return $options;
    }

    /**
     * @return bool
     */
    public function process()
    {
        parent::process();

        $model = $this->getForm()->getModel();
        $options = $model->getPaymentGatewayOptions();

        $files = ['file' => 'certificate', 'private-key' => 'privateKey'];
        foreach ($files as $file => $variable) {
            $fileData = $this->getForm()->getElement('mobilpay[' . $file . ']')->getValue();
            if (is_array($fileData) && $fileData['tmp_name']) {
                $content = file_get_contents($fileData["tmp_name"]);
                $options[$variable] = $content;
            }
        }

        $model->setPaymentGatewayOptions($options);
        $model->saveRecord();

        return $options;
    }
}
