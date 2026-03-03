<?php
/*
2010/03/15(yyyy/mm/dd)
File modified by:
Daniel Hartmann 
daniel@solis.coop.br
Solis - Developer
www.solis.coop.br

changelog
- whitespaces after and before '='


2007/05/09(yyyy/mm/dd)
File modified by:
Fernando Kochhann 
fernando@solis.coop.br
Solis - Developer
www.solis.coop.br

changelog
- class created;
- set atributes
- breakline before '{'

*/

/*
    TODO-LIST:
    ==========
    {there are also TODO's in codes too -- check them before implementing the items listed here}


    wizindent_put_spaces()
    ----------------------
    * make it compatible with wizindent_indent_html()


    wizindent_indent_php()
    ----------------------
    * support indentation of heredoc syntax
    * determine class member variables
    * determine used/created lambda functions
    * determine defined constants
    * cooperate with wizindent_indent_html()


    wizindent_indent_html()
    -----------------------
    * handle strings that contain escape sequences
    * cooperate with wizindent_put_spaces()

*/
class wizindent
{
/* BEGIN: GLOBAL VARIABLES */
var $indent_level,$indent_size,$pharenthesis_count,$bracket_just_be_opened,$bracket_just_be_closed,$in_unbreakable_line,$prev_put_newline,$token_id;
public $insidePharenthesis = 0;
function __construct()
{
$this->indent_level=0;
$this->indent_size=4;
$this->pharenthesis_count=0;
$this->bracket_just_be_opened=false;
$this->bracket_just_be_closed=false;
$this->in_unbreakable_line=false;
/* END: GLOBAL VARIABLES */

// Compatibility work-arounds
if (!defined('T_ML_COMMENT')) {
    define('T_ML_COMMENT', T_COMMENT);
} else {
    define('T_DOC_COMMENT', T_ML_COMMENT);
    @define('T_INSTANCEOF', 100000);
    @define('T_CLONE', 100001);
    @define('T_TRY', 100002);
    @define('T_CATCH', 100003);
    @define('T_THROW', 100004);
    @define('T_PUBLIC', 100005);
    @define('T_PROTECTED', 100006);
    @define('T_PRIVATE', 100007);
    @define('T_FINAL', 100008);
    @define('T_ABSTRACT', 100009);
    @define('T_INTERFACE', 100010);
    @define('T_IMPLEMENTS', 100011);
}

// Token id's of PHP5 tokens (to support backward compatibility with PHP4)
$this->token_id['clone']=T_CLONE;
$this->token_id['abstract']=T_ABSTRACT;
$this->token_id['interface']=T_INTERFACE;
$this->token_id['final']=T_FINAL;
$this->token_id['private']=T_PRIVATE;
$this->token_id['protected']=T_PROTECTED;
$this->token_id['public']=T_PUBLIC;
$this->token_id['throw']=T_THROW;
$this->token_id['impelements']=T_IMPLEMENTS;
$this->token_id['instanceof']=T_INSTANCEOF;
$this->token_id['catch']=T_CATCH;
$this->token_id['try']=T_TRY;
}

function wizindent_put_spaces() {
    if ($this->indent_level && ($this->bracket_just_be_opened || $this->bracket_just_be_closed || $this->prev_put_newline) && !$this->pharenthesis_count && !$this->in_unbreakable_line) {
        return str_repeat(' ', $this->indent_level*$this->indent_size);
    } else {
        return '';
    }
}

function wizindent_indent_php($source) {

    $result='';
    $tokens=token_get_all($source);

    $close_this=array();
    $block_was=array();
    $class_was=array();

    $unindent_this=array();
    $unindent_this_alternative=array();

    $in_open_tag_with_echo=false;
    $in_string=false;
    $in_reserved_block=false;
    $in_case_pattern=false;

    $this->prev_put_newline=false;
    $prev_was_reserved_block=false;

    $bracket_should_be_opened=false;

    $this->bracket_just_be_closed_prev=false;
    $this->bracket_just_be_opened_prev=false;
    $this->bracket_just_be_opened_normally=false;

    $definitions['functions']=array();
    $definitions['constants']=array();
    $definitions['classes']=array();

    $frequency['functions']=array();
    $frequency['constants']=array();
    $frequency['classes']=array();

    foreach ($tokens as $k=>$token) {
        if (is_string($token)) {
            if ($in_string) {
                $result.=$token;

                if ($token == '"') {
                    $in_string=false;
                }

                continue;
            }

            if ($this->bracket_just_be_closed) {
                $result.="\n";

                if ($token != '}') {
                    $result.="\n";
                }
            }

            $this->bracket_just_be_opened_prev=$bracket_just_be_opened;
            $this->bracket_just_be_closed_prev=$bracket_just_be_closed;
            $this->bracket_just_be_opened=false;
            $this->bracket_just_be_closed=false;

            switch ($token) {
                case '(':
                    $result .= '(';

                    if ($prev_was_reserved_block) {
                        $this->prev_put_newline=false;
                        $in_reserved_block=true;
                        $this->in_unbreakable_line=true;
                        $prev_was_reserved_block=false;
                    } else {
                        ++$this->pharenthesis_count;
                    }

                    $this->insidePharenthesis++;

                    break;
                case ')':
                    $result.=')';

                    if ($this->pharenthesis_count) {
                        --$this->pharenthesis_count;
                    } elseif ($in_reserved_block) {
                        $bracket_should_be_opened=true;
                        $in_reserved_block=false;
                        $this->in_unbreakable_line=false;
                    }

                    $this->insidePharenthesis--;

                    break;
                case '{':
                    if ($tokens[$k-1][0] == T_VARIABLE) {
                        $this->in_unbreakable_line=true;
                    }

                    if ($this->in_unbreakable_line) {
                        $result.='{';
                        break;
                    }

                    //$result.=" {\n";
                    // modified by fernando@solis.coop.br
                    $this->prev_put_newline=true;
                    $result.="\n".$this->wizindent_put_spaces()."{\n";
                    // ^
                    ++$this->indent_level;

                    $bracket_should_be_opened=false;
                    $this->bracket_just_be_opened=true;
                    $this->bracket_just_be_opened_normally=true;
                    break;
                case '}':
                    if ($this->in_unbreakable_line) {
                        $result.='}';
                        break;
                    }

                    if ($close_this[$this->indent_level]) {
                        unset ($close_this[$this->indent_level], $block_was[$indent_level]);
                        --$this->indent_level;
                        $this->prev_put_newline=true;
                        $result.=$this->wizindent_put_spaces()."}\n";
                    }

                    if ($unindent_this_alternative[$this->indent_level]) {
                        unset ($unindent_this_alternative[$this->indent_level]);
                        --$this->indent_level;
                    }

                    unset ($block_was[$this->indent_level]);
                    --$this->indent_level;

                    if ($unindent_this[$this->indent_level]) {
                        unset ($unindent_this[$this->indent_level]);
                        --$this->indent_level;
                    }

                    $this->prev_put_newline=true;
                    $result.=$this->wizindent_put_spaces().'}';
                    $this->bracket_just_be_closed=true;
                    $this->prev_put_newline=true;
                    break;
                case ';':
                    if (!$in_reserved_block) {
                        $this->prev_put_newline=true;
                    }

                    $result.=';'.($in_reserved_block ? ' ' : ($in_open_tag_with_echo ? '' : "\n"));

                    if ($bracket_should_be_opened) {
                        $result.="\n";
                    }

                    $bracket_should_be_opened=false;

                    if ($close_this[$this->indent_level]) {
                        unset ($close_this[$this->indent_level]);
                        --$this->indent_level;
                        $result.=$this->wizindent_put_spaces().'}';
                        $this->prev_put_newline=true;
                        $this->bracket_just_be_closed=true;
                    }

                    break;
                case '>':
                case '<':
                case '?':
                    $result.=' '.$token.' ';
                    break;
                case ':':
                    if ($in_case_pattern) {
                        $result.=":\n";
                        ++$this->indent_level;
                        $in_case_pattern=false;
                        $this->in_unbreakable_line=false;
                        $this->prev_put_newline=true;
                        $block_was[$this->indent_level]=T_CASE;
                        $this->bracket_just_be_opened=true;
                        $this->bracket_just_be_opened_normally=true;
                    } elseif ($bracket_should_be_opened) {
                        $result.=":\n";
                        ++$this->indent_level;
                        $unindent_this_alternative[$this->indent_level]=true;
                        $this->prev_put_newline=true;
                        $this->bracket_just_be_opened=true;
                        $this->bracket_just_be_opened_normally=true;
                        $bracket_should_be_opened=false;
                    } else {
                        $result.=' : ';
                    }

                    break;
                case ',':
                    $result.=', ';
                    break;
                case '@':
                    if ($this->bracket_just_be_opened_prev) {
                        $this->bracket_just_be_opened_prev=false;
                        $this->bracket_just_be_opened=true;
                    }

                    $result.=$this->wizindent_put_spaces().'@';
                    $this->prev_put_newline=false;
                    $this->bracket_just_be_opened=false;
                    break;
                case '"':
                    $in_string=true;
                    $result.='"';
                    break;
                case '$':
                    $this->in_unbreakable_line=true;
                    $result.=' $';
                    break;
                case '=':
                    $result .= " = ";
                    break;
                default :
                    $result.=$token;
            }
        } else {
            list($id, $text)=$token;

            if ($in_string) {
                $result.=$text;
            } elseif ($id == T_INLINE_HTML) {
                // TODO: call wizindent_indent_html() here
                $result.=$text;
            } elseif ($id == T_WHITESPACE) {
                // FIXME: fixed no space between Exception and variable, but it
                // is letting 2 spaces on comparisons
                if ( $this->in_unbreakable_line && $this->insidePharenthesis > 0 )
                {
                    $result .= $text;
                }
                else
                {
                    unset($tokens[$k]);
                }
            } else {
                if ($id == T_STRING && isset ($this->token_id[strtolower($text)])) {
                    // Fooling the intenter as we support PHP5 tokens.
                    // An example for operating on custom ones
                    $tokens[$k][0]=$id=$this->token_id[strtolower($text)];
                }

                $this->bracket_just_be_opened_prev=($bracket_just_be_opened_normally || $bracket_just_be_opened);

                if ($this->bracket_just_be_opened_normally) {
                    $this->bracket_just_be_opened_normally=false;
                } else {
                    $this->bracket_just_be_opened=false;
                }

                while ($this->bracket_just_be_closed && $close_this[$this->indent_level]) {
                    $this->prev_put_newline=true;

                    if ($block_was[$this->indent_level] == T_IF && ($id == T_ELSE || $id == T_ELSEIF)) {
                        break;
                    }

                    unset ($close_this[$this->indent_level]);
                    --$this->indent_level;
                    $result.="\n".$this->wizindent_put_spaces().'}';
                    $this->prev_put_newline=true;
                    $this->bracket_just_be_closed=true;
                }

                if ($bracket_should_be_opened) {
                    $result.=" {\n";
                    ++$this->indent_level;
                    $bracket_should_be_opened=false;
                    $this->bracket_just_be_opened=true;
                    $close_this[$this->indent_level]=true;
                    $this->in_unbreakable_line=false;
                }

                if ($this->bracket_just_be_closed && ($id != T_ELSE && $id != T_ELSEIF && $id != T_CATCH) && !($id == T_WHILE && $block_was[$this->indent_level] == T_DO)) {
                    $result.="\n";

                    if ($id != T_CASE && $id != T_DEFAULT) {
                        $result.="\n";
                    }
                }

                $this->bracket_just_be_closed_prev=$this->bracket_just_be_closed;
                $this->bracket_just_be_closed=false;

                switch ($id) {
                    case T_OPEN_TAG:
                        $result.='<'."?php\n\n";
                        $in_open_tag_with_echo=false;
                        break;
                    case T_OPEN_TAG_WITH_ECHO:
                        $result.='<'.'?=';
                        $in_open_tag_with_echo=true;
                        break;
                    case T_CLOSE_TAG:
                        if (!$in_open_tag_with_echo) {
                            $result.="\n";
                        } elseif (!$this->prev_put_newline) {
                            $result.=';';
                        }

                        $result.='?'.">\n";
                        break;
                    case T_CASE:
                    case T_DEFAULT:
                        if ($block_was[$this->indent_level-1] != T_SWITCH) {
                            unset ($block_was[$this->indent_level]);
                            --$this->indent_level;
                        }

                        $this->bracket_just_be_opened_prev=false;
                        $this->prev_put_newline=true;
                        $result.=$this->wizindent_put_spaces().$text.' ';
                        $in_case_pattern=true;
                        $this->in_unbreakable_line=true;
                        break;
                    case T_TRY:
                        $result.=$this->wizindent_put_spaces();
                    case T_ELSE:
                        if ($unindent_this_alternative[$this->indent_level]) {
                            unset ($unindent_this_alternative[$this->indent_level]);
                            --$this->indent_level;
                            $result.=$this->wizindent_put_spaces().$text;
                        } else {
                            if ($id == T_ELSE) {
                                $result.="\n".$this->wizindent_put_spaces();
                            }

                            $result.=$text;
                        }

                        $bracket_should_be_opened=true;
                        break;
                    case T_CATCH:
                    case T_ELSEIF:
                        $prev_was_reserved_block=true;

                        if ($unindent_this_alternative[$this->indent_level]) {
                            unset ($unindent_this_alternative[$this->indent_level]);
                            --$this->indent_level;
                            $result.=$this->wizindent_put_spaces();
                        } else {
                            $result.="\n".$this->wizindent_put_spaces();
                        }

                        $result.=$text.' ';
                        break;
                    case T_SWITCH:
                        $unindent_this[$this->indent_level+1]=true;
                    case T_WHILE:
                        if ($id == T_WHILE && $block_was[$this->indent_level] == T_DO) {
                            $result.=' ';
                        }
                    case T_IF:
                    case T_FOR:
                    case T_FOREACH:
                    case T_DO:
                    case T_FUNCTION:
                        if ($this->prev_put_newline && !$this->bracket_just_be_opened && !$this->bracket_just_be_closed_prev) {
                            $result.="\n";
                        }

                        if (!($id == T_WHILE && $block_was[$this->indent_level] == T_DO)) {
                            $result.=$this->wizindent_put_spaces();
                        }

                        $result.=$text.'';
                        $block_was[$this->indent_level]=$id;

                        if ($id != T_DO && $id != T_TRY) {
                            $prev_was_reserved_block=true;
                            $result.=' ';
                            $this->in_unbreakable_line=true;
                        } else {
                            $bracket_should_be_opened=true;
                        }

                        $this->bracket_just_be_closed_prev=false;

                        break;
                    case T_ENDSWITCH:
                        unset ($unindent_this_alternative[$this->indent_level]);
                        --$this->indent_level;
                    case T_ENDDECLARE:
                    case T_ENDFOR:
                    case T_ENDFOREACH:
                    case T_ENDIF:
                    case T_ENDWHILE:
                        unset ($unindent_this_alternative[$this->indent_level]);
                        --$this->indent_level;
                        $result.=$this->wizindent_put_spaces().$text;
                        break;
                    case T_LNUMBER:
                    case T_DNUMBER:
                    case T_CONSTANT_ENCAPSED_STRING:
                    case T_PAAMAYIM_NEKUDOTAYIM:
                    case T_DOUBLE_COLON:
                    case T_OBJECT_OPERATOR:
                        $result.=$text;
                        $this->prev_put_newline=false;
                        break;
                    case T_AS:
                    case T_IS_EQUAL:
                    case T_IS_GREATER_OR_EQUAL:
                    case T_IS_IDENTICAL:
                    case T_IS_NOT_EQUAL:
                    case T_IS_NOT_IDENTICAL:
                    case T_IS_SMALLER_OR_EQUAL:
                    case T_LOGICAL_AND:
                    case T_LOGICAL_OR:
                    case T_LOGICAL_XOR:
                    case T_BOOLEAN_AND:
                    case T_BOOLEAN_OR:
                    case T_SL:
                    case T_SR:
                        // PHP5 tokens
                    case T_EXTENDS:
                    case T_IMPLEMENTS:
                    case T_INSTANCEOF:
                        $result.=' '.$text.' ';
                        $this->prev_put_newline=false;
                        break;
                    case T_CLASS:
                        $block_was[$this->indent_level]=$id;
                    case T_ECHO:
                    case T_RETURN:
                    case T_CONST:
                    case T_EVAL:
                    case T_EXIT:
                    case T_GLOBAL:
                    case T_INCLUDE:
                    case T_INCLUDE_ONCE:
                    case T_REQUIRE:
                    case T_REQUIRE_ONCE:
                    case T_RETURN:
                    case T_ISSET:
                    case T_NEW:
                    case T_PRINT:
                    case T_UNSET:
                    case T_DECLARE:
                    case T_VAR:
                        // PHP5 tokens
                    case T_CLONE:
                    case T_ABSTRACT:
                    case T_INTERFACE:
                    case T_FINAL:
                    case T_STATIC:
                    case T_PRIVATE:
                    case T_PROTECTED:
                    case T_PUBLIC:
                    case T_THROW:
                        if ($this->bracket_just_be_closed_prev) {
                            $this->prev_put_newline=true;
                        }

                        $result.=$this->wizindent_put_spaces().$text.' ';
                        $this->prev_put_newline=false;
                        break;
                    case T_COMMENT:
                    case T_ML_COMMENT:
                    case T_DOC_COMMENT:
                        $result.=$this->wizindent_put_spaces();

                        if (substr($text, 0, 2) == '/'.'*' && substr($text, -2) == '*'.'/' && !$this->in_unbreakable_line) {
                            $result.=join("\n".$this->wizindent_put_spaces(), explode("\n", $text))."\n";
                        } else {
                            $result.=$text;
                        }

                        $this->prev_put_newline=true;
                        break;
                    case T_STRING:
                        if ($text == '"') {
                            $in_string=true;
                            $result.=$text;
                        } else {
                            // class/function/constant analysis tasks are actually done here
                            $i=$k+1;

                            while ($tokens[$i][0] == T_WHITESPACE && isset ($tokens[$i])) {
                                ++$i;
                            }

                            if ($tokens[$i] == '(') {
                                if ($tokens[$k-2][0] == T_FUNCTION) {
                                    // we found a defined function.
                                    // can be a member of a class
                                    $definitions['functions'][]=array('name'=>$text, 'base'=>$class_was[$this->indent_level-1]);
                                } elseif ($tokens[$k-2][0] == T_NEW) {
                                    // a new instance of a class has just been created in examined source.
                                    // it's possible to find the object variable, if there is.
                                    // so, here comes a brand new
                                    // TODO: find object variable that holds the new instance of class
                                    ++$frequency['classes'][$text];
                                } else {
                                    // TODO: determine static class function calls (like funcN in class::funcN())
                                    // TODO: determine class function calls (like funcN in $objN->funcN())
                                    ++$frequency['functions'][$text];
                                }
                            } else {
                                if ($tokens[$k-2][0] == T_CLASS) {
                                    $base='';
                                    if ($tokens[$i][0] == T_EXTENDS) {
                                        ++$i;

                                        while ($tokens[$i][0] == T_WHITESPACE && isset ($tokens[$i])) {
                                            ++$i;
                                        }

                                        $base=$tokens[$i][1];
                                    }

                                    $definitions['classes'][]=array('name'=>$text, 'base'=>$base, 'is_abstract'=>($tokens[$k-4][0] == T_ABSTRACT ? true : false));
                                    $class_was[$this->indent_level]=$text;
                                } else {
                                    // TODO: determine class constants (like constN in classN::constN)
                                    // TODO: determine if static class member of a class is being accessed (like classN in classN::memberN)
                                    if ($tokens[$k-2][0] != T_EXTENDS) {
                                        ++$frequency['constants'][$text];
                                    }
                                }
                            }

                            $result.=$this->wizindent_put_spaces().$text;
                        }

                        break;
                    default :
                        if ($this->bracket_just_be_closed_prev) {
                            $this->prev_put_newline=true;
                        }

                        $result.=$this->wizindent_put_spaces().$text;
                        $this->prev_put_newline=false;
                        break;
                }
            }
        }
    }

    return array(trim($result), $frequency, $definitions);
}

function wizindent_indent_html($input, $doCompress=false) {
    if ($doCompress) {
        $causeIndentIn=$causeNewLine=$causeNewLineAtStart=$causeNewLineAtClosing=$noWhitespaceBefore=array();
    } else {
        $causeIndentIn=array('head', 'body', 'table', 'tr', 'td', 'div', 'p', 'center', 'form', 'map', 'colgroup', 'dl', 'fieldset', 'frameset', 'iframe', 'dir', 'noframes', 'noscript', 'object', 'select', 'thead', 'tbody', 'ul');
        $causeNewLine=array('html', 'br', 'link', 'input', 'meta', 'area', 'param', 'bgsound', 'col', 'frame', '--', 'hr');
        $causeNewLineAtStart=array('head', 'body');
        $causeNewLineAtClosing=array('title', 'style', 'script', 'applet', 'li');
        $noWhitespaceBefore=array('img');
    }

    $leaveAlone=array('script', 'style', 'textarea', 'pre', 'comment');
    $whiteSpace=array(' ', "\n", "\r", "\t");
    $tagEndChars=array(' ', "\n", "\t", '>');
    $inStr=false;
    $inTag=false;
    $indentLevel=0;
    $doIndent=false;
    $output='';
    $tag='';
    $buffer='';
    $char='';
    $preCBuffer='';
    $lastStrDelim='';
    $lastWasClosingTag=false;
    $lastWasText=false;
    $inIgnoreMode=false;
    $len=@strlen($input);

    for ($offset=0; $offset <= $len; ++$offset) {
        $lastChar=$char;
        $char=$input{$offset};
        $buffer=$char;
        $doAppend=false;

        switch ($buffer) {
            case '<':
                $doAppend=true;

                if (!$inTag && !$inStr) {
                    $tag='';
                    $inTag=true;
                    $closingTag=false;
                    $preC='';
                    $indentC='';
                    $tmpC='';
                    $postC='';
                    $doIndentIn=false;
                    $doIndentOut=false;

                    if ($input{$offset+1} == '/') {
                        ++$offset;
                        $closingTag=true;
                        $tmpC='/';
                    } elseif ($input{$offset+1}.$input{$offset+2}.$input{$offset+3} == '!--') {
                        for (++$offset; @substr($tag, @strlen($tag)-2, 2).$input{$offset} != '-->'; ++$offset) {
                            $tag.=$input{$offset};
                        }

                        $buffer.=$tag;
                        --$offset;
                        $tag='--';
                        $char=' ';
                        break;
                    }

                    for (++$offset; !in_array($input{$offset}, $tagEndChars); ++$offset) {
                        $tag.=$input{$offset};
                    }

                    --$offset;

                    if (!in_array($tag, $noWhitespaceBefore) && !empty($preCBuffer)) {
                        $preC=$preCBuffer.$preC;
                    }

                    $preCBuffer='';
                    $tag=strtolower($tag);

                    if (in_array($tag, $causeIndentIn)) {
                        if ($closingTag) {
                            --$indentLevel;
                            $doIndentOut=true;

                            if (!$lastWasClosingTag) {
                                $preC="\n";
                            }
                        } else {
                            ++$indentLevel;
                            $doIndentIn=true;

                            if ($lastWasText) {
                                $preC="\n";
                            }
                        }
                    }

                    if ($closingTag) {
                        $indentC=@str_repeat(' ', (($doIndentOut) ? $indentLevel : ''));
                    } else {
                        if ($doIndentIn) {
                            if ($indentLevel-1 > 0) {
                                $indentC=@str_repeat(' ', $indentLevel-1);
                            }
                        } elseif ($doIndent && ($indentLevel > 0)) {
                            $indentC=@str_repeat(' ', $indentLevel);
                        }
                    }

                    if ($input{$offset+1} == ' ') {
                        $postC=' ';
                    }

                    if (in_array($tag, $causeNewLineAtStart) && !$closingTag) {
                        $preC.="\n";
                    }

                    if (in_array($tag, $noWhitespaceBefore)) {
                        $buffer=$preC.$buffer.$tmpC.$tag.$postC;
                    } else {
                        $buffer=$preC.$indentC.$buffer.$tmpC.$tag.$postC;
                    }

                    $char=' ';

                    if (in_array($tag, $leaveAlone)) {
                        $inIgnoreMode=(($closingTag) ? false : true);
                    }

                    $lastWasText=false;
                }

                break;
            case '>':
                $doAppend=true;

                if ($inTag && !$inStr) {
                    $doIndent=false;
                    $inTag=false;

                    if (in_array($tag, $causeIndentIn) || in_array($tag, $causeNewLine) || (in_array($tag, $causeNewLineAtClosing) && $closingTag)) {
                        $preCBuffer.="\n";
                        $doIndent=true;
                        $lastWasClosingTag=true;
                    }

                    $tag='';
                    $lastWasText=false;
                }

                break;
            case ' ':
            case "\t":
                if ((!$inIgnoreMode || ($inIgnoreMode && $inTag)) && in_array($lastChar, $whiteSpace)) {
                    break;
                }

                $doAppend=true;
                break;
            case "\n":
            case "\r":
                if ($inIgnoreMode) {
                    $doAppend=true;
                }

                break;
            case '"':
            case "'":
                $doAppend=true;

                if ($inIgnoreMode) {
                    break;
                }

                if (!$inStr && $inTag) {
                    $inStr=true;
                    $lastStrDelim=$buffer;
                } elseif ($inStr && ($lastStrDelim == $buffer)) {
                    $inStr=false;
                }

                break;
            default :
                if (!$inIgnoreMode && !$inTag && !$inStr && $doIndent) {
                    $buffer=@str_repeat(' ', $indentLevel).$buffer;
                    $doAppend=true;
                    $doIndent=false;
                }

                if (!empty($preCBuffer)) {
                    $buffer=$preCBuffer.$buffer;
                    $preCBuffer='';
                }

                if (!empty($postCBuffer) && !$inTag && $doAppend) {
                    $buffer.=$postCBuffer;
                    $postCBuffer='';
                }

                $doAppend=true;
                $lastWasClosingTag=false;
                $lastWasText=true;
        }

        if ($doAppend) {
            $output.=$buffer;
        }
    }

    return $output;
}

}
?>
