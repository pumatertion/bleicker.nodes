<?php

namespace Bleicker\Nodes;

use Bleicker\Nodes\NodeTranslationInterface as TranslationInterface;
use Bleicker\Translation\TranslateTrait as TranslateTraitOrigin;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class TranslateTrait
 *
 * @package Bleicker\Nodes
 */
trait TranslateTrait {

	/**
	 * @return Collection
	 */
	public function getTranslations() {
		return $this->translations;
	}

	/**
	 * @param TranslationInterface $translation
	 * @return $this
	 */
	public function removeTranslation(TranslationInterface $translation) {
		if ($this->hasTranslation($translation)) {
			$this->translations->removeElement($this->getTranslation($translation));
		}
		return $this;
	}

	/**
	 * @param TranslationInterface $translation
	 * @return $this
	 * @throws TranslationAlreadyExistsException
	 * @throws PropertyDoesNotExistsException
	 */
	public function addTranslation(TranslationInterface $translation) {
		if ($this->hasTranslation($translation)) {
			throw new TranslationAlreadyExistsException('Translation "' . (string)$translation . '" already exists', 1431005644);
		}
		$translation->setNode($this);
		$this->translations->add($translation);
		return $this;
	}

	/**
	 * @param TranslationInterface $translation
	 * @return boolean
	 */
	public function hasTranslation(TranslationInterface $translation) {
		$expr = Criteria::expr();
		$criteria = Criteria::create();
		$criteria->andWhere(
			$expr->andX(
				$expr->eq('propertyName', $translation->getPropertyName()),
				$expr->eq('locale', $translation->getLocale())
			)
		);
		$matchingTranslations = $this->translations->matching($criteria);
		return (boolean)$matchingTranslations->count();
	}

	/**
	 * @param TranslationInterface $translation
	 * @return TranslationInterface
	 */
	public function getTranslation(TranslationInterface $translation) {
		$expr = Criteria::expr();
		$criteria = Criteria::create();
		$criteria->andWhere(
			$expr->andX(
				$expr->eq('propertyName', $translation->getPropertyName()),
				$expr->eq('locale', $translation->getLocale())
			)
		);
		$matchingTranslations = $this->translations->matching($criteria);
		return $matchingTranslations->first();
	}
}
