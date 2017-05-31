<?php

namespace Blog\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;
use Zend\Filter\ToInt;
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Validator\StringLength;

class Blog implements InputFilterAwareInterface
{
    public $id;
    public $title;
    public $description;

    public $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id = !(empty($data['id'])) ? $data['id'] : null;
        $this->title = !(empty($data['title'])) ? $data['title'] : null;
        $this->description = !(empty($data['description'])) ? $data['description'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
           'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ]
        ]);

        $inputFilter->add([
           'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                'name' => StringLength::class,
                'options' => [
                    'encoding' => 'UTF-8',
                    'min' => 1,
                    'max' => 100,
                ],
            ],
        ]);

        $inputFilter->add([
           'name' => 'description',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                'name' => StringLength::class,
                'options' => [
                    'encoding' => 'UTF-8',
                    'min' => 1,
                    'max' => 100,
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}