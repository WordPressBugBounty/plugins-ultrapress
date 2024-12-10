<?php

/**
 * svg of admin page
 *
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */
?> 
<!-- toast -->
<div aria-live="polite" aria-atomic="true" class="p-0 m-0" style="z-index: 1; position: relative; min-height: 1px;">
    <div class="toast" style="position: fixed; bottom: 0; right: 100;" data-delay="2000">
      <div id="header-toast-ultrapress" class="toast-header  bg-success text-white">
        <strong  class="mr-auto">Ultrapress notification</strong>
        <button type="button " class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span  class="text-white" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body" id="text-toast-ultrapress">
        Hello, world! This is a toast message.
      </div>
    </div>
</div> <!-- end toast -->

<svg  id="meedsvg"  height="80vh" :viewBox="viewBox"  @dblclick="modalNewComp($event)" @mousemove="DragSvg($event)" v-on:click="showCirc = 1;" fill="#fcfcfc;"   style="background-color: rgb(255, 255, 255);z-index: 2;" >

  <image v-if="! data_loaded" :x="width/2" :y="height/2" href="<?php echo plugin_dir_url( __FILE__ );?>img/ajax-loader.gif" height="100" width="100"/>

  <g v-for="(comp, key, x) in currentCircuit.arch" v-bind:key="comp.trigger_of_component" @mousemove="doDrag(key, $event)" @dblclick.stop="" class="circuitdrag"  :id="key" transform="translate(0,0)" fill="white" stroke="green" stroke-width="5">

    <g> 
      <circle v-if="! (key == currentCircuit.id_of_first_component)"  :cx="comp.x" :cy="comp.y" v-on:click="addConnection(key)" :r="InCircle.r"  :fill="InCircle.fill" :stroke="InCircle.stroke" />

      <path v-if="! (key == currentCircuit.id_of_first_component)"  :d="headPath(comp.x,comp.y)" stroke="green"  stroke-width="10" fill="none"/>

      <rect :x="comp.x - 150" :y=" comp.y + 100" width="300" height="150" rx="10" ry="10" v-on:click.stop="currentCompKey = key; showCirc = ''; console.log(comp);"  :fill="(key == currentCircuit.id_of_first_component) ? '#fae5f5'  : '#fff9e8'" stroke="#000000"/>

        <image v-if="comp.composed != 1" :x="comp.x - 50" :y=" comp.y + 110" v-on:click.stop="currentCompKey = key; showCirc = '';" :href="url_of_component(comp, comp.icon_url)" height="100" width="100"/>

        <image v-if="comp.composed == 1" :x="comp.x - 50" :y=" comp.y + 110" v-on:click.stop="currentCompKey = key; showCirc = '';" href="<?php echo plugin_dir_url( __FILE__ );?>img/CC-icon.png" height="100" width="100"/>

        <image v-if="! (key == currentCircuit.id_of_first_component)" :x="comp.x - 145" :y=" comp.y + 105" v-on:click.stop="deleteComponentDialog(key)" href="<?php echo plugin_dir_url( __FILE__ );?>img/Close-2-icon.png" height="50" width="50"/>

        <text :x="comp.x" :y=" comp.y + 238" style="fill: #000000; stroke: none; font-size: 30px; text-anchor: middle; font-weight: bold; -ms-user-select: none; user-select: none" class="">{{comp.name.substring(0, 18)}}</text>

        <line :x1="comp.x - 150" :y1="comp.y + 100" :x2="comp.x + 150" :y2="comp.y + 100" style="stroke:#9b3feb;stroke-width:10" />

        <line v-if=" (key == currentCircuit.id_of_first_component)" :x1="comp.x - 150" :y1="comp.y + 100" :x2="comp.x + 150" :y2="comp.y + 100" style="stroke:red;stroke-width:15" />

    </g>

    <g> 
      <g v-for="(out, keyOutput, oind) in comp.outputs" v-bind:key="keyOutput">
        <rect :x="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind)" :y="comp.y + 325" width="100" height="100" v-on:click.stop="currentOutputKeys=[key, keyOutput];currentCompKey = ''; showCirc = ''; console.log(out);" rx="20" ry="20" fill="#f2f0f5" stroke="#000000" style="stroke:#1d074a;stroke-width:5;opacity:0.5" />

        <image v-if="comp.composed != 1" :x="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind) + 20" :y=" comp.y + 345" :href="url_of_component(comp,out.output_icon_url)" v-on:click.stop="currentCompKey = ''; currentOutputKeys=[key, keyOutput]; showCirc = '';" height="60" width="60"/>

        <image v-if="comp.composed == 1" :x="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind) + 20" :y=" comp.y + 345" href="<?php echo plugin_dir_url( __FILE__ );?>img/Arrow-Back-icon.png" v-on:click.stop="currentCompKey = ''; currentOutputKeys=[key, keyOutput]; showCirc = '';" height="60" width="60"/>

        <line v-if="out.blocked" :x1="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind)" :y1="comp.y + 325" :x2="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind) + 100" :y2="comp.y + 325 + 100" style="stroke:red;stroke-width:10" />

        <line v-if="out.blocked" :x1="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind)" :y1="comp.y + 325 + 100" :x2="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind) + 100" :y2="comp.y + 325" style="stroke:red;stroke-width:10" />

        <path :d="outputPath(comp.x, comp.y, Object.keys(comp.outputs).length, oind)" stroke="black" stroke-width="5" fill="none"/>

        <path :d="outputPathToTrigger(comp.x, comp.y, Object.keys(comp.outputs).length, oind)" stroke="red" stroke-width="8" fill="none"/>

        <circle :cx="outputsCalculX(comp.x, comp.y, Object.keys(comp.outputs).length, oind) + 50" :cy="comp.y + 500" r="25" v-on:click="clickOnOut(key ,keyOutput)" v-on:dblclick="dbclickOnOut(key ,keyOutput, $event)" fill="red" stroke="none" pointer-events="all" />

        <path v-if="out.idOfConnection" :d="connectCircuit(comp.x, comp.y, Object.keys(comp.outputs).length, oind, out.idOfConnection)" stroke="blue" stroke-width="8" fill="none"/>
      </g>
    </g>
  </g>
</svg>

