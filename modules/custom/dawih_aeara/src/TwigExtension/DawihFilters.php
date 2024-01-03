<?php
namespace Drupal\dawih_aeara\TwigExtension;

class DawihFilters extends \Twig\Extension\AbstractExtension
{
  public function getFilters()
  {
    return [
      new \Twig\TwigFilter('extract_social_platform_from_link', array($this, 'extractSocialPlatformFromLink')),
      new \Twig\TwigFilter('to_array_by_newline', array($this, 'toArrayByNewline')),
      new \Twig\TwigFilter('add_http', array($this, 'addHttp')),
      new \Twig\TwigFilter('dawih_embed_video', array($this, 'dawihEmbedVideo')),
      new \Twig\TwigFilter('rawurlencode', array($this, 'dawihRawUrlEncode')),
    ];
  }

  public function getName()
  {
    return 'dawih_aeara.twig_extension';
  }

  public function extractSocialPlatformFromLink($link)
  {
    # extract domain name from $link
    $domain = parse_url($link, PHP_URL_HOST);
    $domain = str_replace('www.', '', $domain);
    $domain = str_replace('m.', '', $domain);
    $domain = str_replace('web.', '', $domain);

    $link = explode('.', $domain);
    $platform_name = $link[0];

    $supported_platforms = [
      'twitter',
      'facebook',
      'instagram',
      'pinterest',
      'tiktok',
      'linkedin',
      'youtube',
      'vimeo',
      'tumblr',
    ];

    if (in_array($platform_name, $supported_platforms)) {
      return 'fa-brands fa-' . $platform_name;
    } else {
      return 'fa-solid fa-link';
    }
  }

  public function toArrayByNewline($str)
  {
    if (empty($str))
      return false;

    $str = str_replace("\r\n", "\n", $str);
    $str = str_replace("\r", "\n", $str);
    $str = str_replace("\n\n", "\n", $str);
    $str = trim($str);

    return explode("\n", $str);
  }

  public function addHttp($url)
  {
    if (empty($url))
      return false;

    if (strpos($url, 'http://') === false && strpos($url, 'https://') === false) {
      $url = 'http://' . $url;
    }

    return $url;
  }

  private function embedVideo($videoUrl)
  {
    // Check if it's a YouTube video
    if (
      preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $videoUrl, $matches) ||
      preg_match('/youtu\.be\/([^\&\?\/]+)/', $videoUrl, $matches)
    ) {
      $videoId = $matches[1];
      $embedCode = '<div class="embed-responsive embed-responsive-16by9">';
      $embedCode .= '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' . $videoId . '"></iframe>';
      $embedCode .= '</div>';
    }
    // Check if it's a Vimeo video
    elseif (preg_match('/vimeo\.com\/([0-9]+)/', $videoUrl, $matches)) {
      $videoId = $matches[1];
      $embedCode = '<div class="embed-responsive embed-responsive-16by9">';
      $embedCode .= '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/' . $videoId . '"></iframe>';
      $embedCode .= '</div>';
    }
    // Assume it's a direct video link (e.g., MP4)
    else {
      $embedCode = '<div class="embed-responsive embed-responsive-16by9">';
      $embedCode .= '<video controls class="embed-responsive-item">';
      $embedCode .= '<source src="' . $videoUrl . '" type="video/mp4">';
      $embedCode .= 'Your browser does not support the video tag.';
      $embedCode .= '</video>';
      $embedCode .= '</div>';
    }

    return $embedCode;
  }

  public function dawihEmbedVideo($video_url)
  {
    return $this->embedVideo($video_url);
  }

  public function dawihRawUrlEncode($string)
  {
    return rawurlencode($string);
  }
}