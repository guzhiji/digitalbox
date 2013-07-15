<?php

/**
 * 
 * 
 * @version 0.1.20130714
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2013 InterBox Core 1.2 for PHP, GuZhiji Studio
 * @package interbox.core.framework
 */
abstract class FormModel extends BoxModel {

    public static $id_naming = 'field_%s';

    function __construct($classname = NULL, $args = array()) {
        parent::__construct(empty($classname) ? __CLASS__ : $classname, $args);
        $this->parentClassName = __CLASS__;
        $this->containerTplName = 'form';
    }

    public function CreateField($fieldname, $type, $label = '', $value = '', $params = array()) {

        $id = sprintf(FormModel::$id_naming, $fieldname);

        if (empty($label)) {
            $label_html = '';
        } else {
            $label = htmlspecialchars($label);
            if ($type == 'readonly')
                $label_html = $label;
            else
                $label_html = "<label for=\"{$id}\">{$label}</label>";
        }

        switch ($type) {
            case 'text':
            case 'password':
            case 'hidden':
                $params['id'] = $id;
                $params['name'] = $fieldname;
                $params['type'] = $type;
                $params['value'] = $value;
                $html = '';
                foreach ($params as $k => $v) {
                    $v = htmlspecialchars($v);
                    if (!empty($html))
                        $html .= ' ';
                    $html .= "{$k}=\"{$v}\"";
                }
                $html = "<input {$html} />";
                if ($type != 'hidden') {
                    $html = $this->TransformTpl('field', array(
                        'field_lable' => $label_html,
                        'field_input' => $html
                            )
                    );
                }

                break;
            case 'readonly':
                $html = $this->TransformTpl('field', array(
                    'field_lable' => $label_html,
                    'field_input' => htmlspecialchars($value)
                        )
                );

                break;
            default:
                $params['id'] = $id;
                $params['name'] = $fieldname;
                $params['type'] = $type;
                $params['label'] = htmlspecialchars($label);
                $params['value'] = htmlspecialchars($value);

                $html = $this->RenderPHPTpl('field_' . $type, $params);

                break;
        }

        return $html;
    }

}
