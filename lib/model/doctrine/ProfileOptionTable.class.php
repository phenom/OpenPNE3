<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class ProfileOptionTable extends Doctrine_Table
{
  public function retrieveByProfileId($profileId)
  {
    $presetOption = $this->generatePresetProfileOption($profileId);
    if ($presetOption)
    {
      return $presetOption;
    }

    return $this->createQuery()
      ->where('profile_id = ?', $profileId)
      ->orderBy('sort_order')
      ->execute();
  }

  public function getMaxSortOrder()
  {
    $result = $this->createQuery()
      ->orderBy('sort_order DESC')
      ->fetchOne();

    if ($result)
    {
      return (int)$result->getSortOrder();
    }

    return 0;
  }

  public function generatePresetProfileOption($profileId)
  {
    $result = array();
    $profile = Doctrine::getTable('Profile')->find($profileId);
    if (!$profile || !$profile->isPreset())
    {
      return $result;
    }

    $list = opToolkit::getPresetProfileList();
    if (!empty($list[$profile->getRawPresetName()]['Choices']))
    {
      foreach ($list[$profile->getRawPresetName()]['Choices'] as $k => $v)
      {
        $option = new opProfileOptionEmulator();
        $option->id = $k;
        $option->value = $v;
        $result[] = $option;
      }
    }

    return $result;
  }
}
