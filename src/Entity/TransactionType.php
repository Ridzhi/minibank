<?php

namespace App\Entity;

enum TransactionType: int
{
    case TOP_UP = 1;
    case INCOMING_TRANSFER = 2;
    case OUTGOING_TRANSFER = 3;
    case PAYMENT = 4;
}
