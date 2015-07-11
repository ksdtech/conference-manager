<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.teal-pink.min.css" /> 
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/mdl_styles.css'; ?>" />
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__header-row">
          <h3>Name &amp; Title</h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="#overview" class="mdl-layout__tab is-active">Users</a>
          <a href="#features" class="mdl-layout__tab">Features</a>
          <a href="#features" class="mdl-layout__tab">Details</a>
          <a href="#features" class="mdl-layout__tab">Technology</a>
          <a href="#features" class="mdl-layout__tab">FAQ</a>
          <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-shadow--4dp mdl-color--accent" id="add">
            <i class="material-icons">add</i>
          </button>
        </div>
      </header>
      <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Admin Actions</span>
        <nav class="mdl-navigation">
          <a href="#overview" class="mdl-navigation__link">Users</a>
          <a href="#features" class="mdl-navigation__link">Features</a>
          <a href="#features" class="mdl-navigation__link">Details</a>
          <a href="#features" class="mdl-navigation__link">Technology</a>
          <a href="#features" class="mdl-navigation__link">FAQ</a>
        </nav>
      </div>
      <main class="mdl-layout__content">
        <div class="mdl-layout__tab-panel is-active" id="overview">

<?php if ($this->session->flashdata('info') || 
$this->session->flashdata('warn') || $this->session->flashdata('error')) { ?>
<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
  <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
    <i class="material-icons">play_circle_filled</i>
  </header>
  <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
    <div class="mdl-card__title">
    <?php 
      if ($this->session->flashdata('error')) { echo 'Error!'; }
      else if ($this->session->flashdata('warn')) { echo 'Warning!'; }
      else { echo 'Info'; }
    ?>
    </div>
    <div class="mdl-card__supporting-text">
      <?php echo $this->session->flashdata('error'); ?>
      <?php echo $this->session->flashdata('warn'); ?>
      <?php echo $this->session->flashdata('info'); ?>
    </div>
  </div>
</section>
<?php } ?>
