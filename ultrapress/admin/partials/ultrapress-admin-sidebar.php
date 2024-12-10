<?php

/**
 * sidebar of admin page
 *
 * @since      1.0.0
 *
 * @package    Midropress
 * @subpackage Midropress/admin/partials
 */
?> 


<template v-if="showCirc">
  <strong><?php _e('description', 'ultrapress'); ?></strong>: {{ currentCircuit.disc }}
  </br>

  <strong><?php _e('trigger of circuit', 'ultrapress'); ?></strong>: {{ currentCircuit.trigger_of_circuit }}
  <hr> 

  <input type="checkbox" id="checkbox" v-model="currentCircuit.ajaxable">
  <label for="checkbox">ajaxable</label>
  <br>
  <strong>package</strong>: 
  <span v-if="currentCircuit.package" class="text-danger">{{currentCircuit.package}}</span>
  <span v-else class="text-danger"><?php _e('not packaged', 'ultrapress'); ?></span>  
  <button type="button"  class="btn btn-primary btn-sm p-0 px-1 small" v-on:click="modal_add_circuit_to_package" title="<?php _e('edit package', 'ultrapress'); ?>"> 
    <span class="small"><?php _e('update package', 'ultrapress'); ?></span>
  </button>
  <hr>

  <strong><?php _e('triggers of circuit', 'ultrapress'); ?></strong>
  <br> 

  <ul v-if="currentCircuit.triggers">
    <li v-for="(trigger, keyTrig, x) in currentCircuit.triggers" v-bind:key="keyTrig">
      <strong> {{keyTrig}} </strong>: 
      <button type="button"  class="btn btn-secondary btn-sm p-0 px-1 small" v-on:click="deleteTrig(keyTrig)" title="<?php _e('delete trigger', 'ultrapress'); ?>"> 
        <span class="small"><?php _e('del', 'ultrapress'); ?></span>
      </button>

      <ul>
        <li v-for="(arg, keyArg, x) in trigger.args_options" v-bind:key="keyArg">
          *** <strong>{{keyArg}}</strong>:  
          <button type="button"  class="btn btn-secondary btn-sm p-0 px-1 small" v-on:click="editTrig(keyArg, keyTrig)"  title="<?php _e('edit trigger', 'ultrapress'); ?>"> 
            <span class="small"><?php _e('edit', 'ultrapress'); ?></span>
          </button>
       </li>
      </ul>
    </li>
  </ul>

  <br>
   <button type="button"  class="btn btn-primary btn-sm small" v-on:click="modalNewTrig" title="<?php _e('add new Trigger', 'ultrapress'); ?>"> 
      <?php _e('add new trigger', 'ultrapress'); ?>      
  </button>
  <hr>

  <strong><?php _e('activate', 'ultrapress'); ?></strong>: <br>

  <ul v-if="currentCircuit.activate">
    <li v-for="(comp, keyComp) in currentCircuit.activate" v-bind:key="keyComp">
      <strong> {{keyComp }} </strong>: 
      <button type="button"  class="btn btn-danger btn-sm btn-sm p-0 px-1 small" v-on:click="deleteActivate(keyComp)" title="<?php _e('delete activate component', 'ultrapress'); ?>"> 
        <span class="small"><?php _e('del', 'ultrapress'); ?></span>
      </button>

      <ul v-if="currentCircuit.activate">
        <li v-for="(arg, keyArg, x) in comp" v-bind:key="keyArg">
          *** <strong>{{keyArg}}</strong>:  
          <button type="button"  class="btn btn-secondary btn-sm small btn-sm p-0 px-1" v-on:click="editActivateArg(keyArg, keyComp)" title="<?php _e('edit activate arg', 'ultrapress'); ?>"> 
            <i class="fas fa-marker fa-xs m-0"></i> 
            <span class="small"><?php _e('edit', 'ultrapress'); ?></span>
            
          </button>
        </li>
      </ul>
    </li>
  </ul>
  <br>

  <button type="button"  class="btn btn-primary btn-sm small" v-on:click="modalNewActivate"> 
      <?php _e('add new activate', 'ultrapress'); ?>        
  </button>
  <hr>

  <strong> <?php _e('deactivate', 'ultrapress'); ?>   </strong>: <br> 
  <ul v-if="currentCircuit.deactivate">
    <li v-for="(comp, keyComp) in currentCircuit.deactivate" v-bind:key="keyComp">
      <strong> {{keyComp }} </strong>: 
      <button type="button"  class="btn btn-danger btn-sm btn-sm p-0 px-1" v-on:click="deleteDeactivate(keyComp)" title="<?php _e('delete deactivate component', 'ultrapress'); ?>"> 
        <span class="small"><?php _e('del', 'ultrapress'); ?></span>
      </button>

      <ul>
        <li v-for="(arg, keyArg, x) in comp" v-bind:key="keyArg">
          *** <strong>{{keyArg}}</strong>:  
          <button type="button"  class="btn btn-secondary btn-sm btn-sm p-0 px-1" v-on:click="editDeactivateArg(keyArg, keyComp)" title="<?php _e('edit deactivate arg', 'ultrapress'); ?>"> 
            <span class="small"><?php _e('edit', 'ultrapress'); ?></span>
          </button>
        </li>
      </ul>
    </li>
  </ul>
  <br>

  <button type="button"  class="btn btn-primary btn-sm small" v-on:click="modalNewDeactivate"> 
      <?php _e('add new deactivate', 'ultrapress'); ?>          
  </button>
</template>

<template v-else-if="currentCompKey">
  <p>
    <strong>{{ currentCircuit.arch[currentCompKey].name }}</strong>: {{ currentCircuit.arch[currentCompKey].disc }}
  </p>
  <hr>

  <p>
    <strong class="text-danger"><?php _e('input args', 'ultrapress'); ?> </strong>: </br>  
    <ul>
      <li v-if="currentCircuit.arch[currentCompKey]" v-for="(inp, keyInp, x) in currentCircuit.arch[currentCompKey].input" v-bind:key="inp.name">
        <strong>{{keyInp}}<span v-if="inp.required == 1" class="text-danger">*</span></strong>: {{inp.description}} 

        <button v-if="currentCompKey && (currentCircuit.arch[currentCompKey].trigger_of_component == 'ultra/ultra_node')
        || (currentCircuit.arch[currentCompKey].trigger_of_component == 'ultra/ultra_single_node')"
          type="button"
          class="btn btn-secondary btn-sm p-0 px-1" 
          v-on:click="deleteArgNode(keyInp)" 
          title="<?php _e('delete arg', 'ultrapress'); ?>"> 
          <span class="small"><?php _e('del', 'ultrapress'); ?></span>
        </button>
      </li>
    </ul>

    <button v-if="currentCompKey &&
             ( (currentCircuit.arch[currentCompKey].trigger_of_component == 'ultra/ultra_node')
            || (currentCircuit.arch[currentCompKey].trigger_of_component == 'ultra/ultra_single_node') )" type="button"  class="btn btn-secondary btn-sm" 
            v-on:click="addNewArgNodeInfo"
            title="<?php _e('add new arg', 'ultrapress'); ?>"> 
               <?php _e('add new arg ', 'ultrapress'); ?>   
    </button>
  </p>   
</template>

<template v-else-if="currentOutputKeys">
  <template v-if="currentOutputKeys && (currentCircuit.arch[currentOutputKeys[0]].trigger_of_component == 'ultra/ultra_single_node')">
    
    <strong><?php _e('description of output', 'ultrapress'); ?><strong>: <br>
    <input v-model="currentOutput.description" placeholder="edit me" class="form-control-plaintext border">
  </template>
    
  <template v-else>
    {{currentOutput.description}} <br>
  </template>

  <input type="checkbox" id="checkbox" v-model="currentOutput.blocked" 
  @change="currentOutput.blocked = currentOutput.blocked ? 1  : 0;">
  <label for="checkbox"><?php _e('blocked', 'ultrapress'); ?></label> 
  <hr>

  <strong class="text-danger"> <?php _e('output args', 'ultrapress'); ?> </strong>: </br> 
  <ul>
    <li v-for="(inp, key, x) in currentOutput.args" v-bind:key="inp.name">
      <input type="checkbox" id="key" value="key" v-model="inp.exported" @change="changeExportedArg(key)">
      <strong>{{key}}</strong>: {{inp.description}}            
    </li>
  </ul>

  <template v-if="currentOutputKeys && currentOutput && componentConnectedTocurrentOutput && currentCircuit.arch[componentConnectedTocurrentOutput]">
  <p>
    <strong class="text-danger"><?php _e('args options', 'ultrapress'); ?> </strong>: </br> 
    <ul>
      <li v-for="(inp, keyInp, x) in currentCircuit.arch[componentConnectedTocurrentOutput].input" v-bind:key="inp.name">
        <strong>{{keyInp}}<span v-if="inp.required  == 1" class="text-danger">*</span></strong>: 
        <button type="button"  class="btn btn-secondary btn-sm p-0 px-1 small" v-on:click="editArg(keyInp, inp)" title="<?php _e('edit arg', 'ultrapress'); ?>"> 
          <span class="small"><?php _e('edit', 'ultrapress'); ?></span>
        </button>
      </li>
    </ul>
    <hr> 
  </p> 

  </template>
  </p>
</template>
