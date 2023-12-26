<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/rhythm/templates/node/node--nd-blog.html.twig */
class __TwigTemplate_a80b7e409b7a9bf9f7c81c1db576889e extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 70
        $context["classes"] = [0 => "node", 1 => ("node--type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source,         // line 72
($context["node"] ?? null), "bundle", [], "any", false, false, true, 72), 72, $this->source))), 2 => ((twig_get_attribute($this->env, $this->source,         // line 73
($context["node"] ?? null), "isPromoted", [], "method", false, false, true, 73)) ? ("node--promoted") : ("")), 3 => ((twig_get_attribute($this->env, $this->source,         // line 74
($context["node"] ?? null), "isSticky", [], "method", false, false, true, 74)) ? ("node--sticky") : ("")), 4 => (( !twig_get_attribute($this->env, $this->source,         // line 75
($context["node"] ?? null), "isPublished", [], "method", false, false, true, 75)) ? ("node--unpublished") : ("")), 5 => ((        // line 76
($context["view_mode"] ?? null)) ? (("node--view-mode-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(($context["view_mode"] ?? null), 76, $this->source)))) : ("")), 6 => "clearfix"];
        // line 80
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("classy/node"), "html", null, true);
        echo "
<article";
        // line 81
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 81), 81, $this->source), "html", null, true);
        echo ">
  ";
        // line 82
        if (($context["teaser"] ?? null)) {
            // line 83
            echo "    <div class=\"blog-item\">
      <div class=\"blog-item-date\">
          <span class=\"date-num\">";
            // line 85
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["node"] ?? null), "getCreatedTime", [], "method", false, false, true, 85), 85, $this->source), "d"), "html", null, true);
            echo "</span>";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t(twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["node"] ?? null), "getCreatedTime", [], "method", false, false, true, 85), 85, $this->source), "M")));
            echo "
      </div>
      ";
            // line 87
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 87, $this->source), "html", null, true);
            echo "
      <!-- Post Title -->
      <h2 class=\"blog-item-title font-alt\"><a href=\"";
            // line 89
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 89, $this->source), "html", null, true);
            echo "\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null), 89, $this->source), "html", null, true);
            echo "</a></h2>
      ";
            // line 90
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 90, $this->source), "html", null, true);
            echo "
      <!-- Author, Categories, Comments -->
      <div class=\"blog-item-data\">
          <a href=\"#\"><i class=\"fa fa-clock-o\"></i> ";
            // line 93
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["node"] ?? null), "getCreatedTime", [], "method", false, false, true, 93), 93, $this->source), "d"), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t(twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["node"] ?? null), "getCreatedTime", [], "method", false, false, true, 93), 93, $this->source), "F")));
            echo " </a>
          <span class=\"separator\">&nbsp;</span>
          <a href=\"#\"><i class=\"fa fa-user\"></i>";
            // line 95
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_name"] ?? null), 95, $this->source), "html", null, true);
            echo "</a>
          <span class=\"separator\">&nbsp;</span>
          ";
            // line 97
            if (twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "field_blog_category", [], "any", false, false, true, 97)) {
                // line 98
                echo "            <i class=\"fa fa-folder-open\"></i>
            ";
                // line 99
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "field_blog_category", [], "any", false, false, true, 99), 99, $this->source), "html", null, true);
                echo "
            <span class=\"separator\">&nbsp;</span>
          ";
            }
            // line 102
            echo "          <a href=\"#\"><i class=\"fa fa-comments\"></i> ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["comment_count"] ?? null), 102, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Comments"));
            echo "</a>
      </div>
      
      <!-- Text Intro -->
      <div class=\"blog-item-body\">
          ";
            // line 107
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 107, $this->source), "field_blog_category", "links"), "html", null, true);
            echo "
      </div>
      
      <!-- Read More Link -->
      <div class=\"blog-item-foot\">
          <a href=\"";
            // line 112
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 112, $this->source), "html", null, true);
            echo "\" class=\"btn btn-mod btn-round  btn-small\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Read More"));
            echo " <i class=\"fa fa-angle-right\"></i></a>
      </div>
        
    </div>
  ";
        } else {
            // line 117
            echo "    <div class = \"blog-item mb-80 mb-xs-40\">
      <div class = \"blog-item-body\">
        <header>
          ";
            // line 120
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 120, $this->source), "html", null, true);
            echo "
          <h1 ";
            // line 121
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => "node__title", 1 => "mt-0", 2 => "font-alt"], "method", false, false, true, 121), 121, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "field_second_title", [], "any", false, false, true, 121), 121, $this->source), "html", null, true);
            echo "</h1>
          ";
            // line 122
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 122, $this->source), "html", null, true);
            echo "
          ";
            // line 123
            if (($context["display_submitted"] ?? null)) {
                // line 124
                echo "            <div class=\"node__meta\">
              ";
                // line 125
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_picture"] ?? null), 125, $this->source), "html", null, true);
                echo "
              <span";
                // line 126
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author_attributes"] ?? null), 126, $this->source), "html", null, true);
                echo ">
                ";
                // line 127
                echo t("Submitted by @author_name on @date", array("@author_name" => ($context["author_name"] ?? null), "@date" => ($context["date"] ?? null), ));
                // line 128
                echo "              </span>
              ";
                // line 129
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["metadata"] ?? null), 129, $this->source), "html", null, true);
                echo "
            </div>
          ";
            }
            // line 132
            echo "        </header>
        <div";
            // line 133
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "node__content", 1 => "clearfix"], "method", false, false, true, 133), 133, $this->source), "html", null, true);
            echo ">
          <div class = \"lead mt-0\">
            ";
            // line 135
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "field_lead_text", [], "any", false, false, true, 135), 135, $this->source), "html", null, true);
            echo "  
          </div>
          ";
            // line 137
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 137, $this->source), "field_lead_text", "field_second_title", "field_comments"), "html", null, true);
            echo "
        </div>
      </div>
    </div>
    <div class = \"mb-80 mb-xs-40\">
      ";
            // line 142
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content"] ?? null), "field_comments", [], "any", false, false, true, 142), 142, $this->source), "html", null, true);
            echo "
    </div>
    <div class=\"clearfix mt-40\">
      <a href=\"";
            // line 145
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prev"] ?? null), 145, $this->source), "html", null, true);
            echo "\" class=\"blog-item-more left\"><i class=\"fa fa-angle-left\"></i>&nbsp;";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Prev post"));
            echo "</a>
      <a href=\"";
            // line 146
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["next"] ?? null), 146, $this->source), "html", null, true);
            echo "\" class=\"blog-item-more right\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Next post"));
            echo "&nbsp;<i class=\"fa fa-angle-right\"></i></a>
    </div>
  ";
        }
        // line 149
        echo "  
</article>

";
    }

    public function getTemplateName()
    {
        return "themes/custom/rhythm/templates/node/node--nd-blog.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  220 => 149,  212 => 146,  206 => 145,  200 => 142,  192 => 137,  187 => 135,  182 => 133,  179 => 132,  173 => 129,  170 => 128,  168 => 127,  164 => 126,  160 => 125,  157 => 124,  155 => 123,  151 => 122,  145 => 121,  141 => 120,  136 => 117,  126 => 112,  118 => 107,  107 => 102,  101 => 99,  98 => 98,  96 => 97,  91 => 95,  84 => 93,  78 => 90,  72 => 89,  67 => 87,  60 => 85,  56 => 83,  54 => 82,  50 => 81,  46 => 80,  44 => 76,  43 => 75,  42 => 74,  41 => 73,  40 => 72,  39 => 70,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Bartik's theme implementation to display a node.
 *
 * Available variables:
 * - node: The node entity with limited access to object properties and methods.
 *   Only method names starting with \"get\", \"has\", or \"is\" and a few common
 *   methods such as \"id\", \"label\", and \"bundle\" are available. For example:
 *   - node.getCreatedTime() will return the node creation timestamp.
 *   - node.hasField('field_example') returns TRUE if the node bundle includes
 *     field_example. (This does not indicate the presence of a value in this
 *     field.)
 *   - node.isPublished() will return whether the node is published or not.
 *   Calling other methods, such as node.delete(), will result in an exception.
 *   See \\Drupal\\node\\Entity\\Node for a full list of public properties and
 *   methods for the node object.
 * - label: The title of the node.
 * - content: All node items. Use {{ content }} to print them all,
 *   or print a subset such as {{ content.field_example }}. Use
 *   {{ content|without('field_example') }} to temporarily suppress the printing
 *   of a given child element.
 * - author_picture: The node author user entity, rendered using the \"compact\"
 *   view mode.
 * - metadata: Metadata for this node.
 * - date: Themed creation date field.
 * - author_name: Themed author name field.
 * - url: Direct URL of the current node.
 * - display_submitted: Whether submission information should be displayed.
 * - attributes: HTML attributes for the containing element.
 *   The attributes.class element may contain one or more of the following
 *   classes:
 *   - node: The current template type (also known as a \"theming hook\").
 *   - node--type-[type]: The current node type. For example, if the node is an
 *     \"Article\" it would result in \"node--type-article\". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node--view-mode-[view_mode]: The View Mode of the node; for example, a
 *     teaser would result in: \"node--view-mode-teaser\", and
 *     full: \"node--view-mode-full\".
 *   The following are controlled through the node publishing options.
 *   - node--promoted: Appears on nodes promoted to the front page.
 *   - node--sticky: Appears on nodes ordered above other non-sticky nodes in
 *     teaser listings.
 *   - node--unpublished: Appears on unpublished nodes visible only to site
 *     admins.
 * - title_attributes: Same as attributes, except applied to the main title
 *   tag that appears in the template.
 * - content_attributes: Same as attributes, except applied to the main
 *   content tag that appears in the template.
 * - author_attributes: Same as attributes, except applied to the author of
 *   the node tag that appears in the template.
 * - title_prefix: Additional output populated by modules, intended to be
 *   displayed in front of the main title tag that appears in the template.
 * - title_suffix: Additional output populated by modules, intended to be
 *   displayed after the main title tag that appears in the template.
 * - view_mode: View mode; for example, \"teaser\" or \"full\".
 * - teaser: Flag for the teaser state. Will be true if view_mode is 'teaser'.
 * - page: Flag for the full page state. Will be true if view_mode is 'full'.
 * - readmore: Flag for more state. Will be true if the teaser content of the
 *   node cannot hold the main body content.
 * - logged_in: Flag for authenticated user status. Will be true when the
 *   current user is a logged-in member.
 * - is_admin: Flag for admin user status. Will be true when the current user
 *   is an administrator.
 *
 * @see template_preprocess_node()
 */
#}
{%
  set classes = [
    'node',
    'node--type-' ~ node.bundle|clean_class,
    node.isPromoted() ? 'node--promoted',
    node.isSticky() ? 'node--sticky',
    not node.isPublished() ? 'node--unpublished',
    view_mode ? 'node--view-mode-' ~ view_mode|clean_class,
    'clearfix',
  ]
%}
{{ attach_library('classy/node') }}
<article{{ attributes.addClass(classes) }}>
  {% if teaser %}
    <div class=\"blog-item\">
      <div class=\"blog-item-date\">
          <span class=\"date-num\">{{ node.getCreatedTime()|date('d') }}</span>{{ node.getCreatedTime()|date('M')|t }}
      </div>
      {{ title_prefix }}
      <!-- Post Title -->
      <h2 class=\"blog-item-title font-alt\"><a href=\"{{ url }}\">{{ label }}</a></h2>
      {{ title_suffix }}
      <!-- Author, Categories, Comments -->
      <div class=\"blog-item-data\">
          <a href=\"#\"><i class=\"fa fa-clock-o\"></i> {{ node.getCreatedTime()|date('d') }} {{ node.getCreatedTime()|date('F')|t }} </a>
          <span class=\"separator\">&nbsp;</span>
          <a href=\"#\"><i class=\"fa fa-user\"></i>{{ author_name }}</a>
          <span class=\"separator\">&nbsp;</span>
          {% if content.field_blog_category %}
            <i class=\"fa fa-folder-open\"></i>
            {{ content.field_blog_category }}
            <span class=\"separator\">&nbsp;</span>
          {% endif %}
          <a href=\"#\"><i class=\"fa fa-comments\"></i> {{ comment_count }} {{ 'Comments'|t}}</a>
      </div>
      
      <!-- Text Intro -->
      <div class=\"blog-item-body\">
          {{ content|without('field_blog_category', 'links') }}
      </div>
      
      <!-- Read More Link -->
      <div class=\"blog-item-foot\">
          <a href=\"{{ url }}\" class=\"btn btn-mod btn-round  btn-small\">{{ 'Read More'|t }} <i class=\"fa fa-angle-right\"></i></a>
      </div>
        
    </div>
  {% else %}
    <div class = \"blog-item mb-80 mb-xs-40\">
      <div class = \"blog-item-body\">
        <header>
          {{ title_prefix }}
          <h1 {{ title_attributes.addClass('node__title', 'mt-0', 'font-alt') }}>{{ content.field_second_title }}</h1>
          {{ title_suffix }}
          {% if display_submitted %}
            <div class=\"node__meta\">
              {{ author_picture }}
              <span{{ author_attributes }}>
                {% trans %}Submitted by {{ author_name }} on {{ date }}{% endtrans %}
              </span>
              {{ metadata }}
            </div>
          {% endif %}
        </header>
        <div{{ content_attributes.addClass('node__content', 'clearfix') }}>
          <div class = \"lead mt-0\">
            {{ content.field_lead_text }}  
          </div>
          {{ content|without('field_lead_text', 'field_second_title', 'field_comments') }}
        </div>
      </div>
    </div>
    <div class = \"mb-80 mb-xs-40\">
      {{ content.field_comments}}
    </div>
    <div class=\"clearfix mt-40\">
      <a href=\"{{ prev }}\" class=\"blog-item-more left\"><i class=\"fa fa-angle-left\"></i>&nbsp;{{ 'Prev post'|t }}</a>
      <a href=\"{{ next }}\" class=\"blog-item-more right\">{{ 'Next post'|t }}&nbsp;<i class=\"fa fa-angle-right\"></i></a>
    </div>
  {% endif %}
  
</article>

", "themes/custom/rhythm/templates/node/node--nd-blog.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/themes/custom/rhythm/templates/node/node--nd-blog.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 70, "if" => 82, "trans" => 127);
        static $filters = array("clean_class" => 72, "escape" => 80, "date" => 85, "t" => 85, "without" => 107);
        static $functions = array("attach_library" => 80);

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'trans'],
                ['clean_class', 'escape', 'date', 't', 'without'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
