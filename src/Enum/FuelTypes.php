<?php

namespace App\Enum;
// This Enum will allow us to set up the different possible propulsion systems for a registered vehicle :
enum FuelTypes:string
{
    case GASOLINE = 'gasoline';
    case DIESEL = 'diesel';
    case AUTOGAS = 'autogas';
    case ETHANOL = 'ethanol';
    case BIODIESEL = 'biodiesel';
    case BIOGASOLINE = 'biogasoline';
    case PROPANE = 'propane';
    case NATURAL_GAS = 'natural_gas';
    case HYDROGEN = 'hydrogen';
    case ELECTRIC = 'electric';
    case HYBRID = 'hybrid';

}