<?php

namespace Notion\Databases;

enum DatabaseParentType: string
{
    case Page = "page_id";
    case Workspace = "workspace";
}
