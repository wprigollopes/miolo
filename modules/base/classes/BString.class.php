<?php
/**
 * Copyright 2005-2017 de Solis Soluções Livres Ltda.
 *
 * Este arquivo é parte do programa SolisGE/Sagu.
 *
 * O SolisGE/Sagu é um software de propriedade da SOLIS, sendo desenvolvido
 * e mantido exclusivamente por esta empresa.
 *
 * A licença de uso está disponível mediante aquisição exclusiva junto à
 * SOLIS. A licença é concedida sem caráter de exclusividade ao licenciado.
 * Os direitos de uso são perpétuos.
 *
 * Embora os códigos fontes sejam fornecidos, o software é de propriedade
 * da SOLIS, não sendo permitido ao adquirente da licença a sua revenda,
 * empréstimo ou cessão (onerosa ou não) à terceiros. Também não é permitido,
 * a qualquer título e tempo, promover no software qualquer tipo de alienação,
 * reprodução, distribuição, divulgação, registro, licenciamento, transferência
 * ou qualquer outro ato que prejudique ou comprometa os direitos de propriedade
 * de software, o nome e a imagem da sua proprietária e do próprio software,
 * além de configurar concorrência à SOLIS.
 *
 * O licenciado, com o acesso ao código fonte do software, terá o direito de
 * promover mudanças no respectivo código. No entanto, nas situações em que ele
 * contar com o suporte oficial prestado pela SOLIS, não poderá promover mudanças
 * no código fonte, sob pena de perda do referido suporte.
 *
 * Para conhecer em detalhes o Termo de Licenciamento do Software SolisGE/Sagu
 * leia o arquivo “LICENCA.txt” disponível junto ao código deste software. e
 * 
 * 
 *
 *
 *
 *
 *
 **/
class BString
{
    private $string;
    private $encoding = 'UTF-8';
    
    /**
     * Constroi objeto
     *
     * @param string $string Conteudo inicial
     * @param string $encoding Codificacao
     */
    public function __construct($string = null, $encoding = null)
    {
        // Caso nao tenha passado codificacao, obtem da constante
        if ( $encoding == null && defined('BASE_ENCODING') )
        {
            $encoding = BASE_ENCODING;
        }
        
        // Define a codificação somente se ela foi passada por parâmetro ou se está definida a constante BASE_ENCODING, caso contrário usa UTF-8.
        if ( $encoding )
        {
            $this->setEncoding($encoding);
        }
        
        $this->setString($string);
    }

    /**
     * Contrutor estático usado para que possa se utilizar
     * o construtor e chamar a função necessária na mesma linha.
     *
     * @param string $string
     * @return BString
     *
     * @example BString::construct( $string )->generate() = retorna a string em formato de usuário
     */
    public static function construct( $string, $encoding = null )
    {
        return new BString($string, $encoding);
    }

    
    /**
     * Define a string
     * 
     * @param $string
     */
    public function setString($string)
    {
        $this->string = $this->_convert( $string );
    }

    /**
     * Retorna a string na codificação necessária
     *
     * @param string $string
     * @return string retorna a string na codificação necessária
     */
    protected function _convert( $string )
    {
        return $string;
    }

    /**
     * Adiciona algum texto a string.
     *
     * Passa pela função de conversão para garantir a string esteja na codificação utilizada.
     *
     * @param string $string texto a ser adicionado
     */
    public function append( $string )
    {
        $this->string .= $this->_convert( $string ) ;
    }

    /**
     * Troca um contéudo por outro, na string atual.
     * Além disso retorna a nova string
     *
     * @param string $search conteúdo original, a buscar
     * @param string $replace novo conteúdo a subistituir
     * @param string retorna a nova string
     */
    public function replace( $search, $replace )
    {
        $this->string = str_replace($search, $replace, $this->string );
        
        return $this;
    }

    /**
     * Converte o texto para minusculas
     *
     * @return BString
     */
    public function toLower()
    {
        $this->string = mb_strtolower( $this->string,$this->getEncoding() );

        return $this;
    }

    /**
     * Converte o texto para maisculas
     *
     * @return BString
     */
    public function toUpper()
    {
        $this->string = mb_strtoupper( $this->string ,$this->getEncoding() );

        return $this;
    }

    /**
     * Retorna o caracter solicitado pelo parametro index
     *
     * @param integer $index indice do caracter a obter
     * @return char retorna o caracter solicitado
     */
    public function charAt($index)
    {
        return $this->string[ $index ];
    }
   
    /**
     * Obtém a string
     * 
     * @return dia
     */
    public function getString()
    {
    	return $this->string;
    }

    /**
     * Seta a codificação
     *
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
    	$this->encoding = $encoding;
    }

    /**
     * Obtém a codificação
     *
     * @return dia
     */
    public function getEncoding()
    {
    	return $this->encoding;
    }

    /**
     * Verifica se a string é UTF8
     *
     * @param string o texto a verificar
     * @return boolean
     */
    public static function isUTF8( $string )
    {
        //return mb_detect_encoding($this->getString(), 'UTF-8', true);
        //return iconv('ISO-8859-1', 'UTF-8', iconv('UTF-8', 'ISO-8859-1', $string ) ) == $string;
        return BString::checkEncoding($string, 'UTF-8');
    }

    /**
     * Verifica se a string é da codificação passada
     *
     * @param string $string
     * @param string $enc
     * @return boolean
     */
    public static function checkEncoding( $string , $enc  )
    {
        return BString::detectEncoding( $string ) == $enc;
    }

    /**
     * Retorna a codifificação da string
     *
     * @param string $string
     * @return string retorna a codifificação da string
     */
    public static function detectEncoding($string)
    {
        $encList = array('UTF-8','ISO-8859-1');

        if ( is_array( $encList ) )
        {
            foreach ( $encList as $line => $enc)
            {
                if ( $enc == 'UTF-8' )
                {
                    if ( iconv('ISO-8859-1', 'UTF-8', iconv('UTF-8', 'ISO-8859-1', $string ) ) === $string )
                    {
                        return 'UTF-8';
                    }
                }
                else
                {
                    if ( iconv('UTF-8', $enc, iconv( $enc, 'UTF-8', $string ) ) === $string )
                    {
                        return $enc;
                    }
                }
            }
        }
    }

    /**
     * Retorna o tamnho da string
     *
     * @return tamanho da string
     */
    public function length()
    {
        return mb_strlen( $this->getString() , $this->getEncoding() );
    }

    /**
     * Remove os espaços no inicio e fim do texto
     * 
     * @return BString
     */
    public function trim()
    {
        $this->string = trim($this->string);
        return $this;
    }

    /**
     * Converte a string para caracteres ASCII.
     * Retira acentos e outros caracteres especificos.
     *
     * @return BString
     */
    public function toASCII()
    {
        $this->trim(); //remove espaços
        $content = $this->string;
        $content = preg_replace("/[ÁÀÂÃÄáàâãä]/i", "A", $content);
        $content = preg_replace("/[ÉÈÊËéèêë]/i",   "E", $content);
        $content = preg_replace("/[ÍÌÎÏíìîï]/i",   "I", $content);
        $content = preg_replace("/[ÓÒÔÕÖóòôõö]/i", "O", $content);
        $content = preg_replace("/[ÚÙÛÜúùûü]/i",   "U", $content);
        $content = preg_replace("/[Ññ]/i",         "N", $content);
        $content = preg_replace("/[Çç]/i",         "C", $content);
        $content = preg_replace("/\+/i",           "",  $content);

        $this->string = $content;

        $this->toUpper(); //coloca tudo em maisculas

        return $this;
    }

    /**
     * Corta a string de um ponto inicial, considerando ou não um tamanho
     *
     * @param integer $start posição inicial
     * @param integer $length quantidade de caracteres até o corte / tamanho
     * @return BString
     */
    public function sub($start, $length)
    {
        $this->string = mb_substr( $this->string, $start, $length, $this->getEncoding() );

        return $this;
    }
    
    /**
     * Explode a string retornando um array
     * 
     * @param string $delimiter delimitador
     * @return array array com a string explodida
     */
    public function explode( $delimiter )
    {
        return explode( $delimiter, $this->string );
    }
         
    /**
     * Função chamada automaticamente pelo PHP quando precisa converter objeto para String
     * 
     * @return a data no formato do usuário
     */
    public function __toString()
    {
        //$this->string colocado entre "" para garantir que a string é realmente uma string
        return $this->string;
    }
    
    /**
     * Função que o miolo chama automaticamente, convertendo o objeto para string
     * 
     * @return a data no formato do usuário
     */
    public function generate()
    {
        return $this->getString();
    }
}
?>
