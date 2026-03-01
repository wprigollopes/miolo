<?php
class MState
{
    public $stateVars;
    public $viewState = '';
    private $idElement;

    public function __construct($formid)
    {
        $this->idElement = $formid.'__VIEWSTATE';
        $this->stateVars = array();
        $this->viewState = '';
    }

    public function set($var, $value, $component_name = '')
    {
        if (!$component_name)
        {
            $this->stateVars[$var] = $value;
        }
        else
        {
            $this->stateVars[$component_name][$var] = $value;
        }
    }

    public function get($var, $component_name = '')
    {

        if (!$component_name)
        {
            return isset($this->stateVars[$var]) ? $this->stateVars[$var] : null;
        }
        else
        {
            return isset($this->stateVars[$component_name][$var]) ? $this->stateVars[$component_name][$var] : null;
        }
    }

    public function loadViewState()
    {
        $this->viewState = MIOLO::_REQUEST($this->idElement);

        if ($this->viewState)
        {
            $s = base64_decode($this->viewState);
            $data = json_decode($s, true);
            if ($data === null && $s !== 'null') {
                // Fallback for old serialized data
                $data = @unserialize($s, ['allowed_classes' => false]);
            }
            $this->stateVars = $data;
        }
    }

    public function saveViewState()
    {
        if ($this->stateVars)
        {
            $s = json_encode($this->stateVars);
            $this->viewState = base64_encode($s);
        }
    }

    public function getViewState()
    {
        return $this->viewState;
    }

    public function getIdElement()
    {
        return $this->idElement;
    }
}
