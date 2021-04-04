<?php include_once 'NABackend.php'; ?>
<html lang="en" >
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Angular Material style sheet -->
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="css/custom.css">
</head>
<body ng-app="facebookApp1" ng-cloak>
  <div ng-controller="AppCtrl" layout="column" ng-cloak>

    <md-toolbar>
      <div class="md-toolbar-tools">
        <md-truncate flex>
          My Facebook Pages
        </md-truncate>
        <md-list-item>
          <md-button aria-label="User1">
            User 1 <img ng-src="images/avatar.png" class="md-avatar" alt="User1" />
          </md-button>
        </md-list-item>
      </div>
    </md-toolbar>

    <md-content flex layout-padding>

      <div layout="row">
        <div flex></div>
        <md-button class="md-fab md-primary" aria-label="add" ng-click="showModal($event)">
          <md-icon class="material-icons">add</md-icon>
        </md-button>
      </div>

      <div layout="row">
        <div flex="20" hide-xs></div>
        <md-card flex>
          <md-card-title>
            <md-card-title-text>
              <span class="md-headline">This is the title</span>
            </md-card-title-text>
          </md-card-title>
          <md-card-content>
            <p>
              +353 (0) 12939906
            </p>
            <p>
              email@clientname.com
            </p>
            <p class="md-headline">
              LIKES: 11,000
            </p>
          </md-card-content>
          <md-card-actions layout="row" layout-align="end center">
            <md-button>Delete</md-button>
            <md-button ng-click="showModal($event)">Edit</md-button>
          </md-card-actions>
        </md-card>
        <div flex="20" hide-xs></div>
      </div>

    </md-content>

  </div>

  <!-- Angular Material requires Angular.js Libraries -->
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>

  <!-- Angular Material Library -->
  <script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js"></script>

  <!-- Your application bootstrap  -->
  <script src="js/app.js"></script>

</body>
</html>

<!--
Copyright 2016 Google Inc. All Rights Reserved.
Use of this source code is governed by an MIT-style license that can be in foundin the LICENSE file at http://material.angularjs.org/license.
-->
