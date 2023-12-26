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

/* themes/custom/rhythm/templates/layout/html.html.twig */
class __TwigTemplate_de0fe49e7f874c9da468df9f3eb19f1e extends Template
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
        // line 26
        echo "<!DOCTYPE html>
<html";
        // line 27
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["html_attributes"] ?? null), 27, $this->source), "html", null, true);
        echo ">
  <head>
    <head-placeholder token=\"";
        // line 29
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 29, $this->source));
        echo "\">
    <title>";
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->safeJoin($this->env, $this->sandbox->ensureToStringAllowed(($context["head_title"] ?? null), 30, $this->source), " | "));
        echo "</title>
    <css-placeholder token=\"";
        // line 31
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 31, $this->source));
        echo "\">
    <js-placeholder token=\"";
        // line 32
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 32, $this->source));
        echo "\">
  </head>
  <body";
        // line 34
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null), 34, $this->source), "html", null, true);
        echo ">
    ";
        // line 39
        echo "    <a href=\"#main-content\" class=\"visually-hidden focusable\">
      ";
        // line 40
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Skip to main content"));
        echo "
    </a>
    ";
        // line 42
        if (($context["loader_image"] ?? null)) {
            // line 43
            echo "      <!-- Page Loader -->        
      <div class=\"page-loader\">
          <div class=\"loader\">";
            // line 45
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Loading..."));
            echo "</div>
      </div>
      <!-- End Page Loader -->
    ";
        }
        // line 49
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_top"] ?? null), 49, $this->source), "html", null, true);
        echo "
    ";
        // line 50
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page"] ?? null), 50, $this->source), "html", null, true);
        echo "
    <script src=\"//maps.googleapis.com/maps/api/js?key=";
        // line 51
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["gmap_key"] ?? null), 51, $this->source), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
    ";
        // line 52
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_bottom"] ?? null), 52, $this->source), "html", null, true);
        echo "
    <js-bottom-placeholder token=\"";
        // line 53
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null), 53, $this->source));
        echo "\">
  </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "themes/custom/rhythm/templates/layout/html.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 53,  102 => 52,  98 => 51,  94 => 50,  89 => 49,  82 => 45,  78 => 43,  76 => 42,  71 => 40,  68 => 39,  64 => 34,  59 => 32,  55 => 31,  51 => 30,  47 => 29,  42 => 27,  39 => 26,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override for the basic structure of a single Drupal page.
 *
 * Variables:
 * - logged_in: A flag indicating if user is logged in.
 * - root_path: The root path of the current page (e.g., node, admin, user).
 * - node_type: The content type for the current node, if the page is a node.
 * - head_title: List of text elements that make up the head_title variable.
 *   May contain or more of the following:
 *   - title: The title of the page.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site.
 * - page_top: Initial rendered markup. This should be printed before 'page'.
 * - page: The rendered page markup.
 * - page_bottom: Closing rendered markup. This variable should be printed after
 *   'page'.
 * - db_offline: A flag indicating if the database is offline.
 * - placeholder_token: The token for generating head, css, js and js-bottom
 *   placeholders.
 *
 * @see template_preprocess_html()
 */
#}
<!DOCTYPE html>
<html{{ html_attributes }}>
  <head>
    <head-placeholder token=\"{{ placeholder_token|raw }}\">
    <title>{{ head_title|safe_join(' | ') }}</title>
    <css-placeholder token=\"{{ placeholder_token|raw }}\">
    <js-placeholder token=\"{{ placeholder_token|raw }}\">
  </head>
  <body{{ attributes }}>
    {#
      Keyboard navigation/accessibility link to main content section in
      page.html.twig.
    #}
    <a href=\"#main-content\" class=\"visually-hidden focusable\">
      {{ 'Skip to main content'|t }}
    </a>
    {% if loader_image %}
      <!-- Page Loader -->        
      <div class=\"page-loader\">
          <div class=\"loader\">{{ 'Loading...'|t }}</div>
      </div>
      <!-- End Page Loader -->
    {% endif %}
    {{ page_top }}
    {{ page }}
    <script src=\"//maps.googleapis.com/maps/api/js?key={{ gmap_key }}\" type=\"text/javascript\"></script>
    {{ page_bottom }}
    <js-bottom-placeholder token=\"{{ placeholder_token|raw }}\">
  </body>
</html>
", "themes/custom/rhythm/templates/layout/html.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/themes/custom/rhythm/templates/layout/html.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 42);
        static $filters = array("escape" => 27, "raw" => 29, "safe_join" => 30, "t" => 40);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'raw', 'safe_join', 't'],
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
