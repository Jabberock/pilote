<?php

/*
 * Copyright (C) 2015 Hamza Ayoub, Valentin Chareyre, Sofian Hamou-Mamar,
 * Alain Krok, Wenlong Li, Rémi Patrizio, Yamine Zaidou
 *
 * ________________________________
 *
 * This file is part of Pilote.
 *
 * Pilote is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Pilote is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Pilote.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Pilote\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PiloteUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
