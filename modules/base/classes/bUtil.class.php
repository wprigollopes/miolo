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
 *  Class that manipulates and represents a database table.
 *          
 * @author Jader Osvino Fiegenbaum [jader@solis.coop.br]
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Class created on 25/06/2012
 */
class bUtil
{
    /**
     * Returns whether the current version is MIOLO2 (old)
     *
     * @return boolean
     */
    public static function isMiolo2()
    {
        return preg_match('/2.0/', MIOLO_VERSION);
    }
}
