<?php

/**
 * http://webcheatsheet.com/php/send_email_text_html_attachment.php
 */
final class SendEmail {
    
    private function  __construct() {
    }

    /**
     * Sends an email from an email address (changing the given reply to), to another given
     * to email address, with the given subject and body.
     * @param string $from is an email address like user@domain.com
     * @param string $replyTo is an optional email to have a reply, same format as $from
     * @param string $to is an email for the receiver, on the same type as $from
     * @param string $subject is the subject for the email
     * @param string $body is the text body. All new paragraphs should be created using \n.
     */
    public static function sendHtmlEmail($from, $replyTo, $to, $subject, $body) {
        self::sendTextEmail($from, $replyTo, $to, $subject, $body);
    }

    /**
     * Sends an email from an email address (changing the given reply to), to another given
     * to email address, with the given subject and body.
     * @param string $from is an email address like user@domain.com
     * @param string $replyTo is an optional email to have a reply, same format as $from
     * @param string $to is an email for the receiver, on the same type as $from
     * @param string $subject is the subject for the email
     * @param string $body is the text body. All new paragraphs should be created using \n.
     */
    public static function sendTextEmail($from, $replyTo, $to, $subject, $body) {
        $headers = "From: ".$from;
        if ($replyTo != null && $replyTo != "") {
            $headers = $headers . "\r\nReply-To: ". $replyTo;
        } else {
            $headers = $headers . "\r\nReply-To: ". $from;
        }
        try {
            mail($to, $subject, $body, $headers);
        } catch (Exception $e) {
            $error = array();
            $error["emailServerDown"] = "The Infinity Metrics email server cannot deliever email at this time..." 
                                                                                                    . $e->getMessage();
            throw new InfinityMetricsException("There are errors in the input", $error);
        }
    }
}
?>
