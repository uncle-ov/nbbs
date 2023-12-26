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

/* themes/custom/rhythm/templates/layout/page.html.twig */
class __TwigTemplate_b11a038450b785899b231bd006e9202a extends Template
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
        // line 53
        if (($context["layout_builder_activated"] ?? null)) {
            // line 54
            echo "
    ";
            // line 55
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["nikadevs_page"] ?? null), 55, $this->source), "html", null, true);
            echo "

";
        } else {
            // line 58
            echo "
  <div id=\"page-wrapper\">
    <div id=\"page\">
      <div class = \"container-fluid mb-80 pr-0 pl-0\">
        ";
            // line 62
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 62), 62, $this->source), "html", null, true);
            echo "
      </div>
      <div class = \"container mb-80\">
        ";
            // line 65
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
            echo "
      </div>
      <div class = \"container-fluid pr-0 pl-0\">
        ";
            // line 68
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content_full", [], "any", false, false, true, 68), 68, $this->source), "html", null, true);
            echo "
        ";
            // line 69
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer", [], "any", false, false, true, 69), 69, $this->source), "html", null, true);
            echo "
      </div>
    </div>
  </div>

";
        }
        // line 75
        echo "
<div class=\"local-scroll\">
  <a href=\"#top\" class=\"link-to-top\"><i class=\"fa fa-caret-up\"></i></a>
</div>";
    }

    public function getTemplateName()
    {
        return "themes/custom/rhythm/templates/layout/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 75,  72 => 69,  68 => 68,  62 => 65,  56 => 62,  50 => 58,  44 => 55,  41 => 54,  39 => 53,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Bartik's theme implementation to display a single page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.html.twig template normally located in the
 * core/modules/system directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - base_path: The base URL path of the Drupal installation. Will usually be
 *   \"/\" unless you have installed Drupal in a sub-directory.
 * - is_front: A flag indicating if the current page is the front page.
 * - logged_in: A flag indicating if the user is registered and signed in.
 * - is_admin: A flag indicating if the user has permission to access
 *   administration pages.
 *
 * Site identity:
 * - front_page: The URL of the front page. Use this instead of base_path when
 *   linking to the front page. This includes the language domain or prefix.
 *
 * Page content (in order of occurrence in the default page.html.twig):
 * - node: Fully loaded node, if there is an automatically-loaded node
 *   associated with the page and the node ID is the second argument in the
 *   page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - page.header: Items for the header region.
 * - page.highlighted: Items for the highlighted region.
 * - page.primary_menu: Items for the primary menu region.
 * - page.secondary_menu: Items for the secondary menu region.
 * - page.featured_top: Items for the featured top region.
 * - page.content: The main content of the current page.
 * - page.sidebar_first: Items for the first sidebar.
 * - page.sidebar_second: Items for the second sidebar.
 * - page.featured_bottom_first: Items for the first featured bottom region.
 * - page.featured_bottom_second: Items for the second featured bottom region.
 * - page.featured_bottom_third: Items for the third featured bottom region.
 * - page.footer_first: Items for the first footer column.
 * - page.footer_second: Items for the second footer column.
 * - page.footer_third: Items for the third footer column.
 * - page.footer_fourth: Items for the fourth footer column.
 * - page.footer_fifth: Items for the fifth footer column.
 * - page.breadcrumb: Items for the breadcrumb region.
 *
 * @see template_preprocess_page()
 * @see html.html.twig
 */
#}
{%  if layout_builder_activated %}

    {{ nikadevs_page }}

{% else %}

  <div id=\"page-wrapper\">
    <div id=\"page\">
      <div class = \"container-fluid mb-80 pr-0 pl-0\">
        {{ page.header }}
      </div>
      <div class = \"container mb-80\">
        {{ page.content }}
      </div>
      <div class = \"container-fluid pr-0 pl-0\">
        {{ page.content_full }}
        {{ page.footer }}
      </div>
    </div>
  </div>

{% endif %}

<div class=\"local-scroll\">
  <a href=\"#top\" class=\"link-to-top\"><i class=\"fa fa-caret-up\"></i></a>
</div>", "themes/custom/rhythm/templates/layout/page.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/themes/custom/rhythm/templates/layout/page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 53);
        static $filters = array("escape" => 55);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
                []
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
