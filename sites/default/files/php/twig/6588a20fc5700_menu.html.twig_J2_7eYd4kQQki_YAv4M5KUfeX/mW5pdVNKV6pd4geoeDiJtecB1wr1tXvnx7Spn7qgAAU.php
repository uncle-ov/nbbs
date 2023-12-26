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

/* themes/custom/rhythm/templates/navigation/menu.html.twig */
class __TwigTemplate_734e8ceb3142f4e1586ee3089a4ff528 extends Template
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
        // line 21
        $macros["menus"] = $this->macros["menus"] = $this;
        // line 22
        echo "
";
        // line 23
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [($context["items"] ?? null), ($context["attributes"] ?? null), 0, ($context["sub_class"] ?? null)], 23, $context, $this->getSourceContext()));
        echo "

";
    }

    // line 25
    public function macro_menu_links($__items__ = null, $__attributes__ = null, $__menu_level__ = null, $__sub_class__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "items" => $__items__,
            "attributes" => $__attributes__,
            "menu_level" => $__menu_level__,
            "sub_class" => $__sub_class__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 26
            echo "  ";
            $macros["menus"] = $this;
            // line 27
            echo "  ";
            if (($context["items"] ?? null)) {
                // line 28
                echo "    ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["items"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                    // line 29
                    echo "      <li";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "attributes", [], "any", false, false, true, 29), 29, $this->source), "html", null, true);
                    echo ">
        ";
                    // line 30
                    $context["icon"] = ((twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 30)) ? ("<i class=\"fa toggle-menu-icon fa-angle-down\"></i>") : (""));
                    // line 31
                    echo "        ";
                    $context["class"] = (("class = \"" . $this->sandbox->ensureToStringAllowed(($context["sub_class"] ?? null), 31, $this->source)) . "-has-sub\"");
                    // line 32
                    echo "        <a href = \"";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "url", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                    echo "\" ";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(((twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 32)) ? ($this->sandbox->ensureToStringAllowed(($context["class"] ?? null), 32, $this->source)) : ("")));
                    echo ">";
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["item"], "title", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                    echo "
          ";
                    // line 33
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 33) && (($context["menu_level"] ?? null) == 0))) ? (" <i class=\"fa toggle-menu-icon fa-angle-down\"></i>") : ("")));
                    echo "
          ";
                    // line 34
                    echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar((((twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 34) && (($context["menu_level"] ?? null) > 0))) ? (" <i class=\"fa toggle-menu-icon fa-angle-right right\"></i>") : ("")));
                    echo "
        </a>
        ";
                    // line 36
                    if (twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 36)) {
                        // line 37
                        echo "          <ul class = \"";
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["sub_class"] ?? null), 37, $this->source), "html", null, true);
                        echo "-sub\">
          ";
                        // line 38
                        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_call_macro($macros["menus"], "macro_menu_links", [twig_get_attribute($this->env, $this->source, $context["item"], "below", [], "any", false, false, true, 38), ($context["attributes"] ?? null), (($context["menu_level"] ?? null) + 1), ($context["sub_class"] ?? null)], 38, $context, $this->getSourceContext()));
                        echo "
          </ul>
        ";
                    }
                    // line 41
                    echo "      </li>
    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 43
                echo "  ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "themes/custom/rhythm/templates/navigation/menu.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 43,  119 => 41,  113 => 38,  108 => 37,  106 => 36,  101 => 34,  97 => 33,  88 => 32,  85 => 31,  83 => 30,  78 => 29,  73 => 28,  70 => 27,  67 => 26,  51 => 25,  44 => 23,  41 => 22,  39 => 21,);
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Theme override to display a menu.
 *
 * Available variables:
 * - menu_name: The machine name of the menu.
 * - items: A nested list of menu items. Each menu item contains:
 *   - attributes: HTML attributes for the menu item.
 *   - below: The menu item child items.
 *   - title: The menu link title.
 *   - url: The menu link url, instance of \\Drupal\\Core\\Url
 *   - localized_options: Menu link localized options.
 *   - is_expanded: TRUE if the link has visible children within the current
 *     menu tree.
 *   - is_collapsed: TRUE if the link has children within the current menu tree
 *     that are not currently visible.
 *   - in_active_trail: TRUE if the link is in the active trail.
 */
#}
{% import _self as menus %}

{{ menus.menu_links(items, attributes, 0, sub_class) }}

{% macro menu_links(items, attributes, menu_level, sub_class) %}
  {% import _self as menus %}
  {% if items %}
    {% for item in items %}
      <li{{ item.attributes }}>
        {% set icon = item.below ? '<i class=\"fa toggle-menu-icon fa-angle-down\"></i>' %}
        {% set class = 'class = \"' ~ sub_class ~ '-has-sub\"' %}
        <a href = \"{{ item.url }}\" {{ item.below ? class|raw }}>{{ item.title  }}
          {{ item.below and menu_level == 0 ? ' <i class=\"fa toggle-menu-icon fa-angle-down\"></i>' }}
          {{ item.below and menu_level > 0 ? ' <i class=\"fa toggle-menu-icon fa-angle-right right\"></i>' }}
        </a>
        {% if item.below %}
          <ul class = \"{{ sub_class }}-sub\">
          {{ menus.menu_links(item.below, attributes, menu_level + 1, sub_class) }}
          </ul>
        {% endif %}
      </li>
    {% endfor %}
  {% endif %}
{% endmacro %}
", "themes/custom/rhythm/templates/navigation/menu.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/nbbs/themes/custom/rhythm/templates/navigation/menu.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("import" => 21, "macro" => 25, "if" => 27, "for" => 28, "set" => 30);
        static $filters = array("escape" => 29, "raw" => 32);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['import', 'macro', 'if', 'for', 'set'],
                ['escape', 'raw'],
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
