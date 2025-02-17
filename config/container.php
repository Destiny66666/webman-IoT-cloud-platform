<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: ZZP
 * @Date: 2023-10-10 12:24:56
 * @LastEditors: ZZP
 * @LastEditTime: 2023-10-13 14:57:22
 */
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

 $builder = new \DI\ContainerBuilder();
 $builder->addDefinitions(config('dependence', []));
 $builder->useAutowiring(true);
 $builder->useAttributes(true);
 return $builder->build();