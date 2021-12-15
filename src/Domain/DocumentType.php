<?php

namespace Domain;

enum DocumentType: int
{
    case INVOICE = 1;
    case CREDIT = 2;
    case DEBIT = 3;
}
