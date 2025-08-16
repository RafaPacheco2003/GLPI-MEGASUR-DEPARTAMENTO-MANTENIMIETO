<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* components/logs.html.twig */
class __TwigTemplate_1eb1e6438ecac3ccfb4c90609672a959a352d671bd4d26b3e3b09e8a9231659a extends Template
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
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 33
        yield "
";
        // line 34
        $macros["fields"] = $this->macros["fields"] = $this->loadTemplate("components/form/fields_macros.html.twig", "components/logs.html.twig", 34)->unwrap();
        // line 35
        yield "
";
        // line 36
        if ((($context["total_number"] ?? null) < 1)) {
            // line 37
            yield "   <div class=\"alert alert-info\">
      ";
            // line 38
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("No historical"), "html", null, true);
            yield "
   </div>
";
        } else {
            // line 41
            yield "
   ";
            // line 42
            yield Twig\Extension\CoreExtension::include($this->env, $context, "components/pager.html.twig", ["count" => ($context["filtered_number"] ?? null)]);
            yield "
   <div class=\"table-responsive\">
   <table class=\"table table-hover\">
      <thead>
         <tr>
            <th>";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("ID"), "html", null, true);
            yield "</th>
            <th>";
            // line 48
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_n("Date", "Dates", 1), "html", null, true);
            yield "</th>
            <th>";
            // line 49
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\ItemtypeExtension']->getItemtypeName("User"), "html", null, true);
            yield "</th>
            <th>";
            // line 50
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_n("Field", "Fields", 1), "html", null, true);
            yield "</th>
            <th>";
            // line 51
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(_x("name", "Update"), "html", null, true);
            yield "</th>
            <th>
               <span class=\"float-end log-toolbar mb-0\">
                  <button class=\"btn btn-sm show_log_filters ";
            // line 54
            yield (((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["filters"] ?? null)) > 0)) ? ("btn-secondary active") : ("btn-outline-secondary"));
            yield "\">
                     <i class=\"fas fa-filter\"></i>
                     <span class=\"d-none d-xl-block\">";
            // line 56
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Filter"), "html", null, true);
            yield "</span>
                  </button>
                  <a href=\"";
            // line 58
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["csv_url"] ?? null), "html", null, true);
            yield "\" class=\"btn btn-sm text-capitalize btn-icon btn-outline-secondary\">
                     <i class=\"fas fa-file-download\"></i>
                     <span class=\"d-none d-xl-block\">";
            // line 60
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("Export"), "html", null, true);
            yield "</span>
                  </a>
               </span>
            </th>
         </tr>

         ";
            // line 66
            if ((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["filters"] ?? null)) > 0)) {
                // line 67
                yield "         <tr class=\"log_history_filter_row\">
            <td>
               <input type=\"hidden\" name=\"filters[active]\" value=\"1\" />
               <input type=\"hidden\" name=\"items_id\" value=\"";
                // line 70
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["items_id"] ?? null), "html", null, true);
                yield "\" />
            </td>
            <td>
               ";
                // line 73
                yield CoreExtension::callMacro($macros["fields"], "macro_dateField", ["filters[date]", (($__internal_compile_0 =                 // line 75
($context["filters"] ?? null)) && is_array($__internal_compile_0) || $__internal_compile_0 instanceof ArrayAccess ? ($__internal_compile_0["date"] ?? null) : null), "", ["no_label" => true, "full_width" => true, "mb" => ""]], 73, $context, $this->getSourceContext());
                // line 82
                yield "
            </td>
            <td>
               <select name=\"filters[users_names][]\" class=\"form-select logs-filter-select-multiple\" multiple>
                  ";
                // line 86
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["user_names"] ?? null));
                foreach ($context['_seq'] as $context["value"] => $context["username"]) {
                    // line 87
                    yield "                     <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue($context["value"]), "html", null, true);
                    yield "\" ";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["filters"] ?? null), "users_names", [], "array", true, true, false, 87) && CoreExtension::inFilter($context["value"], (($__internal_compile_1 = ($context["filters"] ?? null)) && is_array($__internal_compile_1) || $__internal_compile_1 instanceof ArrayAccess ? ($__internal_compile_1["users_names"] ?? null) : null)))) ? ("selected") : (""));
                    yield ">
                        ";
                    // line 88
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue($context["username"]), "html", null, true);
                    yield "
                     </option>
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['value'], $context['username'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 91
                yield "               </select>
            </td>
            <td>
               <select name=\"filters[affected_fields][]\" class=\"form-select logs-filter-select-multiple\" multiple>
                  ";
                // line 95
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["affected_fields"] ?? null));
                foreach ($context['_seq'] as $context["value"] => $context["field"]) {
                    // line 96
                    yield "                     <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["value"], "html", null, true);
                    yield "\" ";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["filters"] ?? null), "affected_fields", [], "array", true, true, false, 96) && CoreExtension::inFilter($context["value"], (($__internal_compile_2 = ($context["filters"] ?? null)) && is_array($__internal_compile_2) || $__internal_compile_2 instanceof ArrayAccess ? ($__internal_compile_2["affected_fields"] ?? null) : null)))) ? ("selected") : (""));
                    yield ">
                        ";
                    // line 97
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["field"], "html", null, true);
                    yield "
                     </option>
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['value'], $context['field'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 100
                yield "               </select>
            </td>
            <td colspan=\"2\">
               <select name=\"filters[linked_actions][]\" class=\"form-select logs-filter-select-multiple\" multiple>
                  ";
                // line 104
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["linked_actions"] ?? null));
                foreach ($context['_seq'] as $context["value"] => $context["action"]) {
                    // line 105
                    yield "                     <option value=\"";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["value"], "html", null, true);
                    yield "\" ";
                    yield (((CoreExtension::getAttribute($this->env, $this->source, ($context["filters"] ?? null), "linked_actions", [], "array", true, true, false, 105) && CoreExtension::inFilter($context["value"], (($__internal_compile_3 = ($context["filters"] ?? null)) && is_array($__internal_compile_3) || $__internal_compile_3 instanceof ArrayAccess ? ($__internal_compile_3["linked_actions"] ?? null) : null)))) ? ("selected") : (""));
                    yield ">
                        ";
                    // line 106
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["action"], "html", null, true);
                    yield "
                     </option>
                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['value'], $context['action'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 109
                yield "               </select>
            </td>
         </tr>
         ";
            }
            // line 113
            yield "
      </thead>
      <tbody>
      ";
            // line 116
            if ((($context["filtered_number"] ?? null) < 1)) {
                // line 117
                yield "         <tr><td colspan=\"6\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(__("No historical matching your filters"), "html", null, true);
                yield "</td></tr>
      ";
            } else {
                // line 119
                yield "         ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["logs"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["entry"]) {
                    // line 120
                    yield "      \t     ";
                    if ((($__internal_compile_4 = $context["entry"]) && is_array($__internal_compile_4) || $__internal_compile_4 instanceof ArrayAccess ? ($__internal_compile_4["display_history"] ?? null) : null)) {
                        // line 121
                        yield "               <tr>
                  <td>";
                        // line 122
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_5 = $context["entry"]) && is_array($__internal_compile_5) || $__internal_compile_5 instanceof ArrayAccess ? ($__internal_compile_5["id"] ?? null) : null), "html", null, true);
                        yield "</td>
                  <td>";
                        // line 123
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((($__internal_compile_6 = $context["entry"]) && is_array($__internal_compile_6) || $__internal_compile_6 instanceof ArrayAccess ? ($__internal_compile_6["date_mod"] ?? null) : null), "html", null, true);
                        yield "</td>
                  <td>";
                        // line 124
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_7 = $context["entry"]) && is_array($__internal_compile_7) || $__internal_compile_7 instanceof ArrayAccess ? ($__internal_compile_7["user_name"] ?? null) : null)), "html", null, true);
                        yield "</td>
                  <td>";
                        // line 125
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Glpi\Application\View\Extension\DataHelpersExtension']->getVerbatimValue((($__internal_compile_8 = $context["entry"]) && is_array($__internal_compile_8) || $__internal_compile_8 instanceof ArrayAccess ? ($__internal_compile_8["field"] ?? null) : null)), "html", null, true);
                        yield "</td>
                  <td colspan=\"2\" style=\"width: 60%\">";
                        // line 126
                        yield (($__internal_compile_9 = $context["entry"]) && is_array($__internal_compile_9) || $__internal_compile_9 instanceof ArrayAccess ? ($__internal_compile_9["change"] ?? null) : null);
                        yield "</td>
               </tr>
            ";
                    }
                    // line 129
                    yield "         ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entry'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 130
                yield "      ";
            }
            // line 131
            yield "      </tbody>
   </table>
   </div>";
            // line 134
            yield "
   ";
            // line 135
            $context["limitdropdown"] = Twig\Extension\CoreExtension::include($this->env, $context, "components/dropdown/limit.html.twig");
            // line 136
            yield "   <div class=\"ms-auto d-inline-flex align-items-center d-none d-md-block mb-2\">
      ";
            // line 137
            yield Twig\Extension\CoreExtension::sprintf(__("Show %s entries"), ($context["limitdropdown"] ?? null));
            yield "
   </div>
";
        }
        // line 140
        yield "


<script type=\"text/javascript\">
\$(function() {
   \$('.logs-filter-select-multiple').select2();
});
</script>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "components/logs.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  287 => 140,  281 => 137,  278 => 136,  276 => 135,  273 => 134,  269 => 131,  266 => 130,  260 => 129,  254 => 126,  250 => 125,  246 => 124,  242 => 123,  238 => 122,  235 => 121,  232 => 120,  227 => 119,  221 => 117,  219 => 116,  214 => 113,  208 => 109,  199 => 106,  192 => 105,  188 => 104,  182 => 100,  173 => 97,  166 => 96,  162 => 95,  156 => 91,  147 => 88,  140 => 87,  136 => 86,  130 => 82,  128 => 75,  127 => 73,  121 => 70,  116 => 67,  114 => 66,  105 => 60,  100 => 58,  95 => 56,  90 => 54,  84 => 51,  80 => 50,  76 => 49,  72 => 48,  68 => 47,  60 => 42,  57 => 41,  51 => 38,  48 => 37,  46 => 36,  43 => 35,  41 => 34,  38 => 33,);
    }

    public function getSourceContext()
    {
        return new Source("", "components/logs.html.twig", "C:\\xampp\\htdocs\\glpi\\templates\\components\\logs.html.twig");
    }
}
