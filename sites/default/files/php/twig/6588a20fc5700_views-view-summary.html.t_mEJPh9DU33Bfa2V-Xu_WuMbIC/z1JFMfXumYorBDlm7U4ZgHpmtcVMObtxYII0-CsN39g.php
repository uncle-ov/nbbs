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

/* core/modules/views/templates/views-view-summary.html.twig */
class __TwigTemplate_7f0dfdd2e99c1a9aff86f43d2cb5c517 extends Template
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
        // line 23
        echo "<ul>
  ";
        // line 24
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["rows"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 25
            echo "    <li><a href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "url", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
            echo "\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->withoutFilter($this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["row"], "attributes", [], "any", false, false, true, 25), "addClass", [0 => ((twig_get_attribute($this->env, $this->source, $context["row"], "active", [], "any", false, false, true, 25)) ? ("is-active") : (""))], "method", false, false, true, 25), 25, $this->source), "href"), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "link", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
            echo "</a>
      ";
            // line 26
            if (twig_get_attribute($this->env, $this->source, ($context["options"] ?? null), "count", [], "any", false, false, true, 26)) {
                // line 27
                echo "        (";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["row"], "count", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                echo ")
      ";
            }
            // line 29
            echo "    </li>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "</ul>
";
    }

    public function getTemplateName()
    {
        return "core/modules/views/templates/views-view-summary.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 31,  63 => 29,  57 => 27,  55 => 26,  46 => 25,  42 => 24,  39 => 23,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Default theme implementation to display a list of summary lines.
 *
 * Available variables:
 * - rows: The rows contained in this view.
 *   Each row contains:
 *   - url: The summary link URL.
 *   - link: The summary link text.
 *   - count: The number of items under this grouping.
 *   - attributes: HTML attributes to apply to each row.
 *   - active: A flag indicating whether the row is active.
 * - options: Flags indicating how the summary should be displayed.
 *   This contains:
 *   - count: A flag indicating whether the count should be displayed.
 *
 * @see template_preprocess_views_view_summary()
 *
 * @ingroup themeable
 */
#}
<ul>
  {% for row in rows %}
    <li><a href=\"{{ row.url }}\"{{ row.attributes.addClass(row.active ? 'is-active')|without('href') }}>{{ row.link }}</a>
      {% if options.count %}
        ({{ row.count }})
      {% endif %}
    </li>
  {% endfor %}
</ul>
", "core/modules/views/templates/views-view-summary.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/core/modules/views/templates/views-view-summary.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("for" => 24, "if" => 26);
        static $filters = array("escape" => 25, "without" => 25);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'without'],
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
