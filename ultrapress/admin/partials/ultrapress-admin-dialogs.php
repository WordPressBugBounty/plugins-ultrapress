
<div id="dialog" title="<?php _e('add component', 'ultrapress'); ?>"> 
  <div class="form-group">
    <label for="new-comp-dialog-select"><?php _e('choose component', 'ultrapress'); ?>:</label> 
    <select v-model="newCompKey" class="form-control form-control-plaintext" id="new-comp-dialog-select">
      <option v-for="(comp, key, x) in components" v-bind:value="key">
        {{ key }}
      </option>
    </select>

    
    <template v-if="newCompKey">
      
      {{ components[ newCompKey ].disc }}
    </template>
    <br><br>

    <button type="button"  class="btn btn-success" v-on:click="addNewComp()"> 
      <?php _e('add component', 'ultrapress'); ?>
    </button>    
  </div>
</div>

<div id="dialog-update-package" title="<?php _e('update package', 'ultrapress'); ?>"> 
  <div class="form-group">
    <label for="new-comp-dialog-select"><?php _e('choose package', 'ultrapress'); ?>:</label> 
    <select v-model="new_name_of_package_for_circuit" class="form-control form-control-plaintext" id="update-package-dialog-select">
      <option v-for="(comp, key, x) in packages" v-bind:value="key">
        {{ key }}
      </option>
    </select>

    
    <template v-if="new_name_of_package_for_circuit">
      
      {{ packages[ new_name_of_package_for_circuit ].desc }}
    </template>
    <br><br>

    <?php $nonce_add_circuit_to_package = wp_create_nonce( 'ultrapress_add_circuit_to_package' ); ?>
    <button type="button" id="add_circuit_to_package" data-nonce="<?php echo $nonce_add_circuit_to_package;?>" class="btn btn-success" v-on:click="add_circuit_to_package"> 
      <?php _e('update package', 'ultrapress'); ?>
    </button>    
  </div>
</div>

<div id="dialog-delete-component" title="delete component">
  <?php _e('Are you sure to delete this component?', 'ultrapress'); ?>
   <br><br>
  <button type="button"  class="btn btn-danger" v-on:click="deleteComponent()"> 
      <?php _e('delete', 'ultrapress'); ?>
  </button>

  <button type="button"  class="btn btn-primary" v-on:click='jQuery( "#dialog-delete-component" ).dialog( "close" ); '> 
   <?php _e('cancel', 'ultrapress'); ?>
  </button>
</div>

<div id="dialog-new-trigger" title="Basic dialog">
  <div class="form-group">
    <label for="new-trig-dialog-select"><?php _e('choose trigger', 'ultrapress'); ?>:</label> 
    <select v-model="newTrigKey" class="form-control form-control-plaintext"  id="new-trig-dialog-select">
        <option v-for="(trig, key, x) in triggers" v-bind:value="key">
                {{ key }}
        </option>
    </select>
    <br>

    <template v-if="newTrigKey">
      <strong><?php _e('description', 'ultrapress'); ?>:</strong>
      <br>
      {{ triggers[ newTrigKey ].disc }}
    </template>
    <br><br>

    <button type="button"  class="btn btn-success" v-on:click="addNewTrig()"> 
      <?php _e('add trigger', 'ultrapress'); ?>
    </button>
  </div>
</div> <!-- end #dialog-new-trigger -->

<div id="dialog-new-activate" title="new activate">
  <div class="form-group">
    <label for="new-activate-dialog-select"><?php _e('choose  component', 'ultrapress'); ?>:</label> 
    <select v-model="newActivateCompKey" class="form-control form-control-plaintext"  id="new-activate-dialog-select">
        <option v-for="(comp, key, x) in components" v-bind:value="key">
                {{ key }}
        </option>
    </select>
    <br>

    <template v-if="newActivateCompKey">
      <strong><?php _e('description', 'ultrapress'); ?>:</strong>
      <br>
      {{ components[ newActivateCompKey ].disc }}
    </template>
    <br>

    <button type="button"  class="btn btn-success" v-on:click="addNewActivate()"> 
      <?php _e('add component', 'ultrapress'); ?>
    </button>
  </div>
</div> <!-- end #dialog-new-activate -->

<div id="dialog-new-deactivate" title="deactivate">
  <div class="form-group">
    <label for="new-deactivate-dialog-select"><?php _e('choose  component', 'ultrapress'); ?>:</label> 
    <select v-model="newDeactivateCompKey" class="form-control form-control-plaintext"  id="new-deactivate-dialog-select">
        <option v-for="(comp, key, x) in components" v-bind:value="key">
            {{ key }}
        </option>
    </select>
    <br>

    <template v-if="newDeactivateCompKey">
      <strong><?php _e('description', 'ultrapress'); ?>:</strong>
      <br>
      {{ components[ newDeactivateCompKey ].disc }}
    </template>
    <br><br>

    <button type="button"  class="btn btn-success" v-on:click="addNewDeactivate()"> 
      <?php _e('add component', 'ultrapress'); ?>
    </button>
  </div>
</div> <!-- end #dialog-new-deactivate -->


<div id="dialog-new-circuit" title="new circuit">
    <p><strong><?php _e('trigger of circuit', 'ultrapress'); ?></strong> :</p> 
    <input v-model="newCircInfo.trigger_of_circuit" placeholder="<?php _e('trigger of circuit', 'ultrapress'); ?>" :class="[newCircInfo.trigger_of_circuit ? 'valide-input'  : 'error-input']" class="form-control-plaintext form-control-sm">
    </br>

    <p><strong><?php _e('Description', 'ultrapress'); ?>:</strong></p> 
    <textarea v-model="newCircInfo.disc" placeholder="<?php _e('Description of circuit', 'ultrapress'); ?>" :class="[newCircInfo.disc ? 'valide-input'  : 'error-input']" rows="3" class="form-control-plaintext  form-control-sm">  </textarea>
    </br>

    <p><strong><?php _e('choose first component', 'ultrapress'); ?>:</strong></p> 
    <select v-model="newCircInfo.first_trigger" :class="[newCircInfo.first_trigger ? 'valide-input'  : 'error-input']" class="form-control-plaintext">
      <option v-for="(comp, key, x) in components" v-bind:value="comp.trigger_of_component">
              {{ key }}
      </option>
    </select>
    </br>

    <button type="button"  class="btn btn-success" v-on:click="addNewCirc()" 
    :class="[(newCircInfo.first_trigger && newCircInfo.name &&  newCircInfo.trigger_of_circuit) ? ''  : 'disabled']"> 
    <?php _e('add circuit', 'ultrapress'); ?>
  </button>
</div> <!-- end #dialog-new-circuit -->



<div id="dialog-new-arg-node" title="Basic dialog">
    <p><?php _e('name of argument', 'ultrapress'); ?>:</p> 
    <input v-model="newArgNode.name" placeholder="edit me"  :class="[newArgNode.name ? 'valide-input'  : 'error-input']" class="form-control-plaintext border">
    </br>

    <p><?php _e('Description', 'ultrapress'); ?>:</p> 
    <textarea v-model="newArgNode.disc" placeholder="add multiple lines" :class="[newArgNode.disc ? 'valide-input'  : 'error-input']" rows="3" class="form-control-plaintext border">  </textarea>
    </br>

    <button type="button"  class="btn btn-success" v-on:click="addNewArgNode()" 
    :class="[(newArgNode.name &&  newArgNode.disc) ? ''  : 'disabled']"> 
    <?php _e('add arg', 'ultrapress'); ?>
  </button>
</div> <!-- end #dialog-new-arg-node -->



<div id="dialog-export-composed-comp" title="Basic dialog">
  <p><strong><?php _e('trigger of composed component', 'ultrapress'); ?>:</strong></p> 
    <input v-model="composedCompInfo.trigger" id="composed_comp_info_trigger" class="form-control-plaintext" placeholder="" :class="[composedCompInfo.trigger ? 'valide-input'  : 'error-input']">
    </br>

    <p><strong><?php _e('name of composed component', 'ultrapress'); ?>:</strong></p>
    <input v-model="composedCompInfo.name" id="composed_comp_info_name"  placeholder="" class="form-control-plaintext"  :class="[composedCompInfo.name ? 'valide-input'  : 'error-input']">
    </br>

    <p><strong><?php _e('Description', 'ultrapress'); ?>:</strong></p>
    <textarea v-model="composedCompInfo.disc" placeholder="" class="form-control-plaintext" :class="[composedCompInfo.disc ? 'valide-input'  : 'error-input']" rows="3" cols="60">  </textarea>
    </br>

    <?php $nonce_export_composed_component = wp_create_nonce( 'ultrapress_export_composed_component' ); ?>
    <button type="button"  class="btn btn-success"  id="export-composed-component" data-nonce="<?php echo $nonce_export_composed_component;?>"  v-on:click="exportComposedComp()" 
    :class="[( composedCompInfo.name &&  composedCompInfo.trigger && composedCompInfo.disc) ? ''  : 'disabled']"> 
    <?php _e('export', 'ultrapress'); ?>
  </button>
</div> <!-- end #dialog-export-composed-comp -->


<div id="dialog-edit-arg" title="Edit agrument" class="small"> 
    <p class="m-0 small"><strong> <?php _e('list of arguments', 'ultrapress'); ?>:</strong></p>

    <ul class="m-0 small">
      <li v-for="(out, keyOut, x) in currentOutput.args"  v-bind:key="keyOut" class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyOut}}]</strong><span v-if="out.type_of_variable">({{out.type_of_variable}})</span>:  {{out.description}}            
      </li>
    </ul>
    <hr  class="m-1">
    
    <ul v-if="currentArg" class="m-0 small">
     
      <li v-for="(inp, keyInp, x) in currentCircuit.arch[componentConnectedTocurrentOutput].input"  v-if="(keyInp != currentArgKey) && (inp.primal == 1) && (currentCircuit.arch[componentConnectedTocurrentOutput].input[currentArgKey].primal != 1)" v-bind:key="keyInp"  class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyInp}}]</strong>:  {{inp.description}}            
      </li>
      <li v-for="(inp, keyInp, x) in currentCircuit.arch[componentConnectedTocurrentOutput].additional_input" v-if="currentCircuit.arch[componentConnectedTocurrentOutput].input[currentArgKey].primal != 1 "v-bind:key="keyInp"  class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyInp}}]</strong>: {{inp.description}}            
      </li>
       <hr  class="m-1">
    </ul>
    
    <template>
      <template v-if="argOptions.type_of_map && (argOptions.type_of_map == 'select')">
        <p class="m-0 small"><strong> <?php _e('select value', 'ultrapress'); ?>:</strong></p>
        <select v-model="argOptions.map"  :class="[argOptions.map ? 'valide-input'  : 'error-input']">
          <option v-for="(type, key_of_option) in values_of_select_argument" v-bind:value="key_of_option">
                  {{ key_of_option }}
          </option>
        </select>
        <br>
        {{argOptions.map}}
      </template>

      <template v-else>
        <p class="m-0 small"><strong> <?php _e('type of mapping', 'ultrapress'); ?>:</strong></p>
        <select v-model="argOptions.type_of_map"  :class="[argOptions.type_of_map ? 'valide-input'  : 'error-input']">
        <option v-for="(type, x) in typeOfMap" v-bind:value="type">
                {{ type }}
        </option>
        </select>
        <template v-if="argOptions.type_of_map">
          <hr>
          <p class="m-0 small"><strong> <?php _e('mapping', 'ultrapress'); ?>:</strong></p>
            <div v-if = "! (argOptions.type_of_map === 'literal')" class="form-group">
               <input v-model="argOptions.map" placeholder="" :class="[argOptions.map ? 'valide-input'  : 'error-input']" class="form-control-plaintext border">
            </div>

            <div v-else class="form-group">
               <textarea v-model="argOptions.map" placeholder=""  class="form-control-plaintext border" :class="[argOptions.map ? 'valide-input'  : 'error-input']" rows="3" cols="60">  </textarea>
            </div>
        </template> 
      </template>
    </template>
    
    </br>

    <button type="button"  class="btn btn-success" v-on:click="saveArgOption()" 
      :class="[(argOptions.map && argOptions.type_of_map) ? ''  : 'disabled']">
      <?php _e('save', 'ultrapress'); ?> 
    </button>

</div> <!-- end #dialog-edit-arg -->



<div id="dialog-edit-activate-arg" title="edit activate arg"> 
    <p class="m-0 small"><strong> <?php _e('type of mapping', 'ultrapress'); ?>:</strong></p>
    <select v-model="argOptionsActivate.type_of_map"  :class="[argOptionsActivate.type_of_map ? 'valide-input'  : 'error-input']">
      <option v-for="(type, x) in typeOfMap" v-bind:value="type">
              {{ type }}
      </option>
    </select>

    <template v-if="argOptionsActivate.type_of_map">
      <hr>
    <p class="m-0 small"><strong> <?php _e('mapping', 'ultrapress'); ?>:</strong></p>
      <template >
        <input v-model="argOptionsActivate.map" placeholder="" :class="[argOptionsActivate.map ? 'valide-input'  : 'error-input']" class="form-control-plaintext border">
      </template>
    </template>
    </br>

    <button type="button"  class="btn btn-success" v-on:click="saveArgOptionsActivate()" 
      :class="[(argOptionsActivate.map && argOptionsActivate.type_of_map) ? ''  : 'disabled']"> 
      <?php _e('save', 'ultrapress'); ?>
    </button>

</div> <!-- end #dialog-edit-activate-arg -->


<div id="dialog-edit-deactivate-arg" title="edit deactivate arg"> 
    <p class="m-0 small"><strong> <?php _e('type of mapping', 'ultrapress'); ?>:</strong></p>

    <select v-model="argOptionsDeactivate.type_of_map"  :class="[argOptionsDeactivate.type_of_map ? 'valide-input'  : 'error-input']">
      <option v-for="(type, x) in typeOfMap" v-bind:value="type">
              {{ type }}
      </option>
    </select>
    <template v-if="argOptionsDeactivate.type_of_map">
      <hr>
      <p class="m-0 small"><strong> <?php _e('mapping', 'ultrapress'); ?>:</strong></p>
      <template >
        <input v-model="argOptionsDeactivate.map" placeholder="" :class="[argOptionsDeactivate.map ? 'valide-input'  : 'error-input']" class="form-control-plaintext border">
      </template>
    </template>
    </br>

    <button type="button"  class="btn btn-success" v-on:click="saveArgOptionsDeactivate()" 
      :class="[(argOptionsDeactivate.map && argOptionsDeactivate.type_of_map) ? ''  : 'disabled']"> 
      <?php _e('save', 'ultrapress'); ?>
    </button>

</div> <!-- end #dialog-edit-deactivate-arg -->



<div id="dialog-edit-trig" title="Basic dialog"> 
    <p class="m-0 small"><strong> <?php _e('list of arguments', 'ultrapress'); ?>:</strong></p>

    <ul v-if="triggers[currentTrig]" class="m-0 small">
      <li v-for="(out, keyOut, x) in triggers[currentTrig].args"  v-bind:key="keyOut" class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyOut}}]</strong><span v-if="out.type_of_variable">({{out.type_of_variable}})</span>:  {{out.disc}}            
      </li>
      <li v-for="(out, keyOut, x) in triggers[currentTrig].additional_args"  v-bind:key="keyOut"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyOut}}]</strong><span v-if="out.type_of_variable">({{out.type_of_variable}})</span>:  {{out.disc}}            
      </li>
      <hr>
      <li v-for="(inp, keyInp, x) in currentCircuit.arch[currentCircuit.id_of_first_component].additional_input"  v-if="(keyInp != currentArgKey) && currentCircuit.arch[currentCircuit.id_of_first_component].input[currentTrigArgKey].primal != 1" v-bind:key="keyInp"  class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyInp}}]</strong>:  {{inp.description}}            
      </li>
    </ul>
    <ul v-if="currentTrigArgKey && currentCircuit.arch[currentCircuit.id_of_first_component] &&  currentCircuit.arch[currentCircuit.id_of_first_component].input && currentCircuit.arch[currentCircuit.id_of_first_component].input[currentTrigArgKey] && (! currentCircuit.arch[currentCircuit.id_of_first_component].input[currentTrigArgKey].primal)" class="m-0 small">

      <li v-for="(inp, keyInp, x) in currentCircuit.arch[currentCircuit.id_of_first_component].input"  v-if="(keyInp != currentTrigArgKey)  && inp.primal" v-bind:key="keyInp"  class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyInp}}]</strong><span v-if="inp.type_of_variable">({{inp.type_of_variable}})</span>:  {{inp.description}}            
      </li>

      <li v-for="(inp, keyInp, x) in currentCircuit.arch[currentCircuit.id_of_first_component].additional_input"  v-if="keyInp != currentTrigArgKey" v-bind:key="keyInp"  class="m-0"> 
        <strong class="text-success font-weight-bold user-select-all">[{{keyInp}}]</strong><span v-if="inp.type_of_variable">({{inp.type_of_variable}})</span>:  {{inp.description}}            
      </li>

    </ul>
    <hr>

    <p class="m-0 small"><strong> <?php _e('type of mapping', 'ultrapress'); ?>:</strong></p>

    <select v-model="argOptionsTrig.type_of_map"  :class="[argOptionsTrig.type_of_map ? 'valide-input'  : 'error-input']">
      <option v-for="(type, x) in typeOfMap" v-bind:value="type">
              {{ type }}
      </option>
    </select>
    <template v-if="argOptionsTrig.type_of_map">
      <hr>
      <p class="m-0 small"><strong> <?php _e('mapping', 'ultrapress'); ?>:</strong></p>

      <template >
        <input v-model="argOptionsTrig.map" placeholder="edit me" :class="[argOptionsTrig.map ? 'valide-input'  : 'error-input']" class="form-control-plaintext border">
      </template>
    </template>
    </br>

    <button type="button"  class="btn btn-success" v-on:click="saveArgOptionsTrig()" 
      :class="[(argOptionsTrig.map && argOptionsTrig.type_of_map) ? ''  : 'disabled']"> 
      <?php _e('save', 'ultrapress'); ?>
    </button>

</div> <!-- end #dialog-edit-Trig -->

  


<div id="dialog-plugin" title="create new plugin">
  <div class="form-group">
    <label for="name-of-package-of-plugin"><?php _e('choose package', 'ultrapress'); ?>:
      <br>  
    <select v-model="package_of_plugin" id="name-of-package-of-plugin" class="form-control" :class="[package_of_plugin ? 'valide-input'  : 'error-input']">
      <option v-for="(circ, key, x) in packages" v-bind:value="key">
        {{ key }}
      </option>
    </select>
  </div>
  <br> <br> 

   <?php $nonce_create_plugin = wp_create_nonce( 'ultrapress_create_plugin' ); ?>
  <button type="button"  id="create-plugin" data-nonce="<?php echo $nonce_create_plugin;?>" class="btn btn-success" v-on:click="createPlugin" 
      :class="[(package_of_plugin) ? ''  : 'disabled']"> 
      <?php _e('send', 'ultrapress'); ?>
  </button>

</div> <!-- end #dialog-plugin -->

<div id="dialog-package" title="<?php _e('create new package', 'ultrapress'); ?>">
  <form class="needs-validation">  
    <div class="form-check form-check-inline">
      <label class="form-check-label">
         <input type="radio" class="form-check-input form-control-sm" name="optradio" id="create-radio" value="create" v-model="create_or_update">
         <?php _e('create package', 'ultrapress'); ?>
      </label> &nbsp;&nbsp;
      <label class="form-check-label">
         <input type="radio" class="form-check-input form-control-sm" name="optradio" id="clone-radio" value="clone" v-model="create_or_update">
         <?php _e('clone package', 'ultrapress'); ?>
      </label>
    </div>
    <hr>  

    <template v-if="create_or_update == 'clone'">  
      <div class="form-group">
        <label for="dialog-package-select"><?php _e('package that you want to clone', 'ultrapress'); ?>:
          <br>  
        <select v-model="package_to_be_cloned" id="name-of-package-to-clone" class="form-control" :class="[package_to_be_cloned ? 'valide-input'  : 'error-input']">
          <option v-for="(circ, key, x) in packages" v-bind:value="key">
            {{ key }}
          </option>
        </select>
      </div>
    </template>


  <p><strong><?php _e('name of package', 'ultrapress'); ?>:</strong></p> 
    <input v-model="nameOfPackage" id="name-of-new-package" class="form-control-plaintext form-control-sm" placeholder="" :class="[nameOfPackage ? 'valide-input'  : 'error-input']" aria-describedby="nameOfPackageHelpBlock" required>
    <div class="valid-feedback">
        Looks good!
    </div>
    <small id="nameOfPackageHelpBlock" class="form-text text-muted">
        <?php _e('use only alphanumeric characters and - and _', 'ultrapress'); ?>    
    </small>
    </br>

  <p><strong><?php _e('description of package', 'ultrapress'); ?>:</strong></p> 
    <textarea v-model="descOfPackage" placeholder="<?php _e('Description of package', 'ultrapress'); ?>" :class="[descOfPackage ? 'valide-input'  : 'error-input']" rows="3" class="form-control-plaintext form-control-sm border">  </textarea>

    <template v-if="create_or_update == 'create'">
        <?php $nonce_create_package = wp_create_nonce( 'ultrapress_create_package' ); ?>
        <button type="button"  id="create-package" data-nonce="<?php echo $nonce_create_package;?>" class="btn btn-success" v-on:click="createPackage()"> 
            <?php _e('create', 'ultrapress'); ?>
        </button>
    </template>
    <template v-else>
      <?php $nonce_clone_package = wp_create_nonce( 'ultrapress_clone_package' ); ?>
        <button type="button"  id="clone-package" data-nonce="<?php echo $nonce_clone_package;?>" class="btn btn-success" v-on:click="clonePackage()"> 
            <?php _e('clone', 'ultrapress'); ?>
        </button>
    </template>
  
</form>

</div> <!-- end #dialog-package -->

<div id="dialog-run" title="Basic dialog">
  <strong> <?php _e('args', 'ultrapress'); ?>:</strong> 
  <br>
  <ul v-if="args_for_execute">
    <li   v-for="(inp, keyInp, x) in args_for_execute" v-bind:key="inp.name">
      <strong>{{keyInp}}<span v-if="inp.required == 1" class="text-danger">*</span></strong>: 
      <input v-model="inp.value" placeholder="edit me" class="form-control-plaintext border"> 
    </li>
  </ul>

  <?php $nonce_run = wp_create_nonce( 'ultrapress_run' ); ?>
  <button type="button" id="run-circuit" class="btn btn-success" data-nonce="<?php echo $nonce_run;?>" v-on:click="run()"> 
      <?php _e('run circuit', 'ultrapress'); ?>
    </button>


</div> <!-- end #dialog-run -->