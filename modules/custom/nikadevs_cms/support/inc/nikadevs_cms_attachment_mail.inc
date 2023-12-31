<?php

/**
 * @file
 */

/**
 *
 */
class AttachmentEmail {
  private $from = '';
  private $from_name = '';
  private $reply_to = '';
  private $to = '';
  private $subject = '';
  private $message = '';
  private $attachment = '';
  private $attachment_filename = '';

  /**
   * Function for defining email.
   *
   * @param $to
   *    Email recipient address
   * @param $from
   *    Email sender address
   * @param $message
   *    Message text of body. (currenlty plain text only)
   *   $param $attachment (optional)
   *    An array containing attachment file name and path
   *    array('filename' => 'attachment.pdf', 'uri' => '/tmp/attachment.pdf')
   **/
  public function __construct($to, $from, $subject, $message, $attachment = array()) {
    $this->to = $to;
    $this->from = $from;
    $this->subject = $subject;
    $this->message = $message;
    $this->attachment = $attachment;
    if (is_array($attachment) && isset($attachment['uri']) && isset($attachment['filename'])) {
      $this->attachment = $attachment['uri'];
      $this->attachment_filename = $attachment['filename'];
    }
  }

  /**
   * Hook for sending actual eamil.
   **/
  public function send() {
    if (!empty($this->attachment)) {
      $filename = is_string($this->attachment) ? 'support.png' : (empty($this->attachment_filename) ? basename($this->attachment) : $this->attachment_filename);
      $mailto = $this->to;
      $from_mail = $this->from;
      $from_name = $this->from_name;
      $replyto = $this->reply_to;
      $subject = $this->subject;
      $message = $this->message;
      $content = is_string($this->attachment) ? $this->attachment : base64_encode(file_get_contents($this->attachment));
      $content = chunk_split($content);
      $uid = md5(uniqid(time()));
      $header = "From: " . $from_mail . "\r\n";
      $header .= "MIME-Version: 1.0\r\n";
      $header .= "Content-Type: multipart/mixed; boundary=" . $uid . "\r\n\r\n";
      $header .= "This is a multi-part message in MIME format.\r\n";
      $header .= "--" . $uid . "\r\n";
      $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
      $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
      $header .= $message . "\r\n\r\n";
      $header .= "--" . $uid . "\r\n";
      // Use diff. tyoes here.
      $header .= "Content-Type: application/octet-stream; name=support.png\r\n";
      $header .= "Content-Transfer-Encoding: base64\r\n";
      $header .= "Content-Disposition: attachment; filename=support.png\r\n\r\n";
      $header .= $content . "\r\n\r\n";
      $header .= "--" . $uid . "--";
      if (mail($mailto, $subject, "", $header)) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
    else {
      $header = "From: " . ($this->from_name) . " <" . ($this->from) . ">\r\n";
      $header .= "Reply-To: " . ($this->reply_to) . "\r\n";
      if (mail($this->to, $this->subject, $this->message, $header)) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
  }

}
