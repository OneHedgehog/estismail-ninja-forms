<?php if (!defined('ABSPATH')) exit;

class Estis_NF_Field extends NF_Fields_Checkbox
{
    protected $_name = 'estis_options';
    protected $_nicename = 'Estismail';
    protected $_section = 'misc';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __('Estismail opt-in', 'estismail-nf-translate');
    }
}