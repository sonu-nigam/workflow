<?php

final class DiffusionRepositoryDatasource
  extends PhabricatorTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Repositories');
  }

  public function getPlaceholderText() {
    return pht('Type a repository name...');
  }

  public function getDatasourceApplicationClass() {
    return PhabricatorDiffusionApplication::class;
  }

  public function loadResults() {
    $query = id(new PhabricatorRepositoryQuery())
      ->setViewer($this->getViewer());

    $this->applyFerretConstraints(
      $query,
      id(new PhabricatorRepository())->newFerretEngine(),
      'title',
      $this->getRawQuery());

    $repos = $query->execute();

    $type_icon = id(new PhabricatorRepositoryRepositoryPHIDType())
      ->getTypeIcon();

    $image_sprite =
      "phabricator-search-icon phui-font-fa phui-icon-view {$type_icon}";

    $results = array();
    foreach ($repos as $repository) {
      $monogram = $repository->getMonogram();
      $name = $repository->getName();

      $display_name = "{$monogram} {$name}";

      $parts = array();
      $parts[] = $name;

      $slug = $repository->getRepositorySlug();
      if (phutil_nonempty_string($slug)) {
        $parts[] = $slug;
      }

      $callsign = $repository->getCallsign();
      if ($callsign) {
        $parts[] = $callsign;
      }

      foreach ($repository->getAllMonograms() as $monogram) {
        $parts[] = $monogram;
      }

      $name = implode("\n", $parts);

      $vcs = $repository->getVersionControlSystem();
      $vcs_type = PhabricatorRepositoryType::getNameForRepositoryType($vcs);

      $result = id(new PhabricatorTypeaheadResult())
        ->setName($name)
        ->setDisplayName($display_name)
        ->setURI($repository->getURI())
        ->setPHID($repository->getPHID())
        ->setPriorityString($repository->getMonogram())
        ->setPriorityType('repo')
        ->setImageSprite($image_sprite)
        ->setDisplayType(pht('Repository'))
        ->addAttribute($vcs_type);

      if (!$repository->isTracked()) {
        $result->setClosed(pht('Inactive'));
      }

      $results[] = $result;
    }

    return $results;
  }

}
