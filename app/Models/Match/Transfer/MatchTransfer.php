<?php

namespace App\Models\Match\Transfer;

use DateTime;

class MatchTransfer
{
    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var int|null
     */
    public ?int $id_team_home = null;

    /**
     * @var int|null
     */
    public ?int $id_team_away = null;

    /**
     * @var DateTime|null
     */
    public ?DateTime $time = null;
}
