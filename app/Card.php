<?php


namespace App;


class Card
{
    const PART_TITLE = "Part";

    public $jid;
    public $id;
    public $html;
    public $date;
    public $process_id;
    public $number;
    public $qty;
    public $workstation;
    public $index_per_day;

    function __construct($jid, $id, $html, $date, $process_id, $number, $qty, $workstation, $index_per_day)
    {
        $this->jid = $jid;
        $this->id = $id;
        $this->html = $html;
        $this->date = $date;
        $this->process_id = $process_id;
        $this->number = $number;
        $this->qty = $qty;
        $this->workstation = $workstation;
        $this->index_per_day = $index_per_day;
    }

    static function generateHtml($jid, $qty, $card_number, $progress_percentage, $template_html)
    {
        //$html = '<div onmouseover="addEvent($(this))" onmouseout="removeEvent($(this))" class="job fill j110022179191 ui-widget-content badge badge-default badge-pill ui-draggable ui-draggable-handle" jid="110022179191" style="background:hsla(116,39%,35%,0.3);color:black" title="30/ Part 6"><div class="json d-none">110022179191-hsla(116,39%,35%,0.3)-00000000000002--000000--00000000-00000000-8100094081-13-2W4 IK46VP \ 13-2W4 PD-F48-110022179191-PR-P-SA-L6-6,028-490,000-2,954-100,000-2,990-200,000-0,036--TOS-1460,00--4,00-M.-16493-VERSUCHSVERSATZ-13-2W4 IK46VP \ 13-2W4 PD-F48-900549-F001--0,15%-342,9-101,6-76,2-3,55-2,56-2,34-KG-2,46-H044-300,000-300,00-IK46VP-5.39901.015110022179191-hsla116393503-00000000000002-000000-00000000-00000000-8100094081-13-2w4-ik46vp-13-2w4-pd-f48-110022179191-pr-p-sa-l6-6028-490000-2954-100000-2990-200000-0036-tos-146000-400-m-16493-versuchsversatz-13-2w4-ik46vp-13-2w4-pd-f48-900549-f001-015-3429-1016-762-355-256-234-kg-246-h044-300000-30000-ik46vp-539901015</div>0 <br>30.11.-0001 <br>13-2W4 IK46VP  <br>200,000 <br>300,000 <br><div class="delete"></div></div>';
        $result = preg_replace('/jid="(.+?)"/', "jid=\"$jid\"", $template_html);
        $result = preg_replace('/title="(.+?)"/', "title=\"$qty/ Part $card_number\"", $result);
        if ($progress_percentage == 100) {
            if (strpos($result, ' checked3="checked"') === false) {
                //if the finish is not marked
                $result = str_replace('><div class="json d-none">', ' checked3="checked"><div class="json d-none">', $result);
            }
        } else if ($progress_percentage > 0) {
            $result = preg_replace('/job(.*) fill halfcheck(.*)- /m', 'job fill', $result);
            $result = preg_replace('/job(.*) fill /m', 'job fill halfcheck-' . $progress_percentage . '- ', $result);
        }

        return $result;
    }

    static function generateHtmlUpdateTitle($jid, $qty, $card_number, $template_html)
    {

        $result = preg_replace('/jid="(.+?)"/', "jid=\"$jid\"", $template_html);
        $result = preg_replace('/title="(.+?)"/', "title=\"$qty/ Part $card_number\"", $result);

        return $result;
    }
}
