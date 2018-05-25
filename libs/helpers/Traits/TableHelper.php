<?php

/**
 * Trait TableHelper
 * convert data array to optimized html table
 */
trait TableHelper
{
    /**
     * convert data array to html table
     * @param array $data
     * @return array
     */
    protected function convertDataTables($data = [])
    {
        foreach ($data AS &$values) {
            if (isset($values['table'])) {
                $prepend = isset($values['prepend']) ? $values['prepend'] : '';

                // build table head
                $html = <<<EOF
                <style>
                    .cktable th,
                    .cktable td {
                        padding: .5em .8em;
                    }
                    .cktable .th { text-align:left; white-space: nowrap; }
                    .cktable tr.th:nth-child(odd) {background: rgba(0,0,0,0.4)}
                    .cktable tr:nth-child(odd) {background: rgba(0,0,0,0.2)}
                    .unicode,.unicode:link,.unicode:visited {border:0;background:transparent;padding:0;font-size:4em;cursor:pointer;color:#FFF;text-decoration:none}
                    .unicode.play {font-size:2.5em;position:relative;top:-0.1em}
                    .unicode.red {color:red}
                    .separator { background: rgba(0,0,0,0.3);font-weight:bold;font-size:1.2em }
                </style>
                $prepend
			<table class="cktable" cellpadding="0" cellspacing="0" width="100%">
				<tr class="th">
EOF;
                foreach ($values['table']['head'] AS $th) {
                    $options = '';
                    if (is_array($th)) {
                        $options = ' ' . $th[1];
                        $th = $th[0];
                    }

                    $html .= '<th class="th"' . $options . '>' . $this->Translate($th) . '</th>';
                }

                $html .= '</tr>';

                // build table body
                foreach ($values['table']['body'] AS $tr) {
                    $html .= '<tr>';
                    foreach ($tr AS $td) {
                        $options = '';
                        if (is_array($td)) {
                            $options = ' ' . $td[1];
                            $td = $td[0];
                        }

                        $html .= '<td' . $options . '>' . $this->Translate($td) . '</td>';
                    }

                    $html .= '</tr>';
                }

                $html .= '</table>';

                // replace value with html
                $values = $html;
            }
        }

        return $data;
    }
}