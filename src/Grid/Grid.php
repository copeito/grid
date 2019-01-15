<?php
namespace copeito;

/**
 *    Properties:
 *      -Model
 */
class Grid extends PropertyManager
{
    private $dataSourceType;

    private $fields = array();
    private $fieldLabels = array();

    public function __construct(array $args)
    {
        unset($this->dataSourceType);
        unset($this->fields);
        unset($this->fieldLabels);

        parent::__construct($args);
    }

    public function __get($name)
    {
        switch($name){
            case 'dataSourceType':
                $this->dataSourceType = ($this->get('dataSource')['type'] ?: 'model');
                break;
            case 'fields':
                $this->fields = $this->get('dataSource')['fields'] ?
                    array_keys($this->get('dataSource')['fields']) :
                    array_keys($reg->getAttributes());
                break;
            case 'fieldLabels':
                foreach($this->fields as $field){
                    $this->fieldLabels[] = $this->get('dataSource')['fields'][$field];
                }
                break;
        }

        return $this->{$name};
    }

    public function __toString()
    {
        return "BestaString";
    }

    private function getData()
    {
        $data = array();

        if ($this->dataSourceType == 'model'){
            $data = $this->get('dataSource')['model']::all(
                $this->fields
            );
        }

        return $data;
    }

    public function render()
    {
        $idx = 0;
        $fieldLabels = $this->fieldLabels;
        $data = array();

        foreach($this->getData() as $reg){
            foreach($this->fields as $field){
                $data[$idx][] = $reg->{$field};
            }
            $idx++;
        }

        return view($this->get('view'), compact('data','fieldLabels'));
    }
}
