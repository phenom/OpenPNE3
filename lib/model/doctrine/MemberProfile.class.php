<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberProfile extends BaseMemberProfile
{
  public function __toString()
  {
    if ('date' !== $this->getFormType())
    {
      if ($this->getProfileOptionId())
      {
        $option = Doctrine::getTable('ProfileOption')->find($this->getProfileOptionId());
        return (string)$option->getValue();
      }

      $children = $this->getChildrenValues(true);
      if ($children)
      {
        return implode(', ', $children);
      }
    }

    return (string)$this->getValue();
  }

  public function construct()
  {
    if (!$this->isNew())
    {
      $this->mapValue('name', $this->Profile->getName());
      Profile::initializeI18n();
      $this->mapValue('caption', $this->Profile->Translation['ja_JP']->caption);
    }

    return parent::construct();
  }

  public function getValue()
  {
    if ('date' !== $this->getFormType() && $this->getProfileOptionId())
    {
      return $this->getProfileOptionId();
    }

    $children = $this->getChildrenValues();
    if ($children)
    {
      if ('date' === $this->getFormType())
      {
        if (count($children) == 3 && $children[0] && $children[1] && $children[2])
        {
          $obj = new DateTime();
          $obj->setDate($children[0], $children[1], $children[2]);
          return $obj->format('Y-m-d');
        }
        return null;
      }
      return $children;
    }

    return parent::rawGet('value');
  }

  protected function getChildrenValues($isToString = false)
  {
    $values = array();

    if ($this->getNode()->hasChildren())
    {
      $children = $this->getNode()->getChildren();
      foreach ($children as $child)
      {
        if ('date' === $child->getFormType())
        {
          $values[] = $child->getValue();
        }
        elseif ($child->getProfileOptionId())
        {
          if ($isToString)
          {
            $option = Doctrine::getTable('ProfileOption')->find($child->getProfileOptionId());
            $values[] = $option->getValue();
          }
          else
          {
            $values[] = $child->getProfileOptionId();
          }
        }
      }
    }

    return $values;
  }

  public function getFormType()
  {
    return $this->Profile->getFormType();
  }

  public function setValue($value)
  {
    if ($this->getProfile()->isSingleSelect() && !$this->getProfile()->isPreset())
    {
      $this->setProfileOptionId($value);
    }
    else
    {
      $this->_set('value', $value);
    }
  }

  public function postSave($event)
  {
    if ($this->getTreeKey())
    {
      $parent = $this->getTable()->find($this->getTreeKey());
      if ($parent)
      {
        $this->getNode()->insertAsLastChildOf($parent);
      }
    }
    else
    {
      $tree = $this->getTable()->getTree();
      $tree->createRoot($this);
    }
  }

  public function isViewable($memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    switch ($this->getPublicFlag())
    {
      case ProfileTable::PUBLIC_FLAG_FRIEND:
        $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->getMemberId(), $memberId);
        if  ($relation && $relation->isFriend())
        {
          return true;
        }

        return ($this->getMemberId() == $memberId);

      case ProfileTable::PUBLIC_FLAG_PRIVATE:
        return false;

      case ProfileTable::PUBLIC_FLAG_SNS:
        return true;
    }
  }

  public function clearChildren()
  {
    if ($this->getTreeKey() && $this->getNode()->hasChildren())
    {
      $children = $this->getNode()->getChildren();
      $children->delete();
    }
  }
}
