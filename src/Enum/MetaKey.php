<?php

namespace Codememory\Reflection\Enum;

enum MetaKey: string
{
    case NAME = 'name';
    case SHORT_NAME = 'short_name';
    case NAMESPACE = 'namespace';
    case ARGUMENTS = 'arguments';
    case ATTRS = 'attributes';
    case TRAITS = 'traits';
    case INTERFACES = 'interfaces';
    case PROPS = 'properties';
    case METHODS = 'methods';
    case TARGET = 'target';
    case PARAMS = 'parameters';
    case IS_REPEATED_KEY = 'is_repeated';
    case INSTANCE = 'instance';
    case IS_FINAL = 'is_final';
    case IS_ABSTRACT = 'is_abstract';
    case IS_READ_ONLY = 'is_read_only';
    case IS_ANONYMOUS = 'is_anonymous';
    case IS_CLONEABLE = 'is_cloneable';
    case IS_ITERABLE = 'is_iterable';
    case IS_TRAIT = 'is_trait';
    case IS_INTERFACE = 'is_interface';
    case IS_CONSTRUCT = 'is_construct';
    case MODIFIER = 'modifier';
    case DEFAULT_VALUE = 'default_value';
    case VALUE = 'value';
    case TYPE = 'type';
    case ALLOW_NULLABLE = 'allow_nullable';
    case IS_BUILTIN = 'is_builtin';
    case PARENT = 'parent';
    case CLASS_CONSTANT = 'class_constant';
    case CUSTOM = 'custom';
    case ID = 'id';
    case HASH = 'hash';
    case HAS_DEFAULT_VALUE = 'has_default_value';
}
