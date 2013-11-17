<?php

namespace Piwicms\System\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class AjaxResponse {

    /**
     * @var string Status of the ajax response (success or error, default = success)
     */
    protected $status = 'success';

    /**
     * @var integer Status code of the ajax response (default 200 = OK)
     */
    protected $code = 200;

    /**
     * @var array Data array
     */
    protected $data = array();

    /**
     * @var array Multiple errors
     */
    protected $errors = array();

    /**
     * @var string Type of reponse (json or xml, default = json)
     */
    protected $type = 'json';

    /**
     * @var object JMS Serializer
     */
    protected $JMSSerializer;

    public function __construct(Serializer $JMSSerializer)
    {
        /** @var $JMSSerializer Serializer */
        $this->JMSSerializer = $JMSSerializer;
    }

    protected function createAjaxResponseObject()
    {
        if ($this->status == 'error') {
            $data = $this->errors;
        } else {
            $data = $this->data;
        }

        $array = array (
            'status' => $this->status,
            'code' => $this->code,
            'data' => $data
        );

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->JMSSerializer->serialize($array, $this->type, $context);
    }

    public function sendResponse()
    {
        // Get response objects
        $responseObject = $this->createAjaxResponseObject();
        // Create response object
        $response = new Response();
        // Set content-type to application/json
        if ($this->type == 'json') {
            $response->headers->set('Content-Type', 'application/json');
        } elseif ($this->type == 'xml') {
            $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');
        }
        // Set content
        $response->setContent($responseObject);
        // Return reponse
        return $response;
    }

    /**
     * @param array $errors
     */
    public function addError($error)
    {
        $this->status = 'error';
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function setErrors($errors)
    {
        $this->status = 'error';
        if (is_array($errors)) {
            $this->errors = $errors;
        } else {
            $this->errors = array($errors);
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return object
     */
    public function getJMSSerializer()
    {
        return $this->JMSSerializer;
    }

}