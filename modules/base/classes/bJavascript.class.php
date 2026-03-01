<?php

/**
 * <--- Copyright 2012 Solis - Cooperativa de Soluções Livres Ltda.
 *
 * This file is part of the Base program.
 *
 * Base is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation (FSF); version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License/GPL
 * for more details.
 *
 * You should have received a copy of the GNU General Public License, under
 * the title "LICENCA.txt", along with this program. If not, visit the
 * Brazilian Public Software Portal at www.softwarepublico.gov.br or write
 * to the Free Software Foundation (FSF) Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA --->
 *
 *  Class that defines the javascript methods that can be used in the system.
 *          
 * @author Eduardo Bonfandini [eduardo@solis.coop.br]
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 *
 * @since
 * Class created on 31/07/2012
 */
class bJavascript
{

    /**
     * Public static method to set focus on the desired field.
     * 
     * @param string $campoId Id of the field where focus will be set.
     * @param boolean $imediato Sets focus immediately if true.
     */
    public static function definirFoco($campoId, $imediato = TRUE)
    {
        $imediato = $imediato ? 'true' : 'false';
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirFoco('$campoId', $imediato);");
    }

    /**
     * Public static method to set a display for the desired field/element.
     * 
     * @param string $campoId Id of the field whose display will be changed.
     * @param boolean $rotulo If true, also changes the label display.
     * @param string $display "display" value for the element. E.g.: block, none.
     */
    public static function definirVisualizacao($campoId, $rotulo = FALSE, $display = 'block')
    {
        $rotulo = $rotulo ? 'true' : 'false';
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirVisualizacao('$campoId', $rotulo, '$display');");
    }

    /**
     * Changes the current display of the element.
     * 
     * @param string $divId Id of the DIV whose display will be changed.
     * @param string $divImagemId Id of the DIV containing images whose display will be changed.
     */
    public static function alterarVisualizacao($divId)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.alterarVisualizacao('$divId', '$divImagemId');");
    }

    /**
     * Public static method to hide elements.
     * 
     * @param string $elementoId Id of the element to hide.
     */
    public static function esconderElemento($elementoId)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirVisualizacao('$elementoId', 'false', 'none');");
    }

    /**
     * Public static method to show elements.
     * 
     * @param string $elementoId Id of the element to show.
     */
    public static function mostrarElemento($elementoId)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirVisualizacao('$elementoId', 'false', 'block');");
    }

    /**
     * Public static method to disable the desired field.
     * 
     * @param string $campoId Id of the field to disable.
     */
    public static function desabilitarCampo($campoId)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.desabilitarCampo('$campoId');");
    }

    /**
     * Public static method to enable the desired field.
     * 
     * @param string $campoId Id of the field to enable.
     */
    public static function habilitarCampo($campoId)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.habilitarCampo('$campoId');");
    }

    /**
     * Public static method to set a value on the desired field.
     * 
     * @param string $campoId Id of the field to set the value on.
     * @param string $valor Value to be set on the field.
     */
    public static function definirValor($campoId, $valor)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirValor('$campoId', '$valor');");
    }

    /**
     * Public static method to set content on a specific element.
     * 
     * @param string $elementoId Id of the element where the content will be set.
     * @param string $conteudo Content to be added to the desired element.
     */
    public static function definirConteudo($elementoId, $conteudo)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirConteudo('$elementoId', '$conteudo');");
    }

    /**
     * Public static method to set a field as read-only.
     * 
     * @param string $elementoId Id of the element where the content will be set.
     * @param boolean somenteLeitura If true, sets the field as read-only.
     */
    public static function definirSomenteLeitura($campoId, $somenteLeitura = TRUE)
    {
        $somenteLeitura = $somenteLeitura ? 'true' : 'false';
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.definirSomenteLeitura('$campoId', $somenteLeitura);");
    }

    /**
     * Public static method to check the desired field.
     * 
     * @param string $campoId Id of the field to check.
     * @param boolean $checar Flag to check the field.
     */
    public static function checarCampo($campoId, $checar = TRUE)
    {
        $checar = $checar ? TRUE : FALSE;
        $MIOLO = MIOLO::getInstance();

        $MIOLO->page->onload("base.checarCampo('$campoId');");
    }
}

?>