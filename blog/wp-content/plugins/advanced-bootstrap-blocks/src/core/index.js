const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
import { CustomColumnInspector } from './coreColumnInspector.js';
import { CustomPaddingInspector } from './corePaddingInspector.js';
import { CustomMarginInspector } from './coreMarginInspector.js';
import { CustomClassNameInspector } from './coreClassNameInspector.js';

addFilter( 
  'editor.BlockEdit', 
  'advanced-custom-blocks/custom-column-inspector', 
  CustomColumnInspector 
);

addFilter( 
  'editor.BlockEdit', 
  'advanced-custom-blocks/custom-padding-inspector', 
  CustomPaddingInspector 
);

addFilter( 
  'editor.BlockEdit', 
  'advanced-custom-blocks/custom-margin-inspector', 
  CustomMarginInspector 
);

addFilter( 
  'editor.BlockEdit', 
  'advanced-custom-blocks/custom-classname-inspector', 
  CustomClassNameInspector 
);

const addSupportReusableCoreBlock = function( settings, name ) {
  if ( name === 'core/block' ) {
      const newSettings = lodash.assign( {}, settings, {
        supports: lodash.assign( {}, settings.supports, {
          align: true, 
          default: 'full'
        } ),
      } );
      settings = newSettings; 
  }
  return settings;
}
addFilter(
  'blocks.registerBlockType',
  'advanced-bootstrap-blocks/core/block',
  addSupportReusableCoreBlock
);

const modifyReusableCoreBlock = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == "core/block") {
      props.attributes.align = props.attributes.align || 'full';
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockContainer' );
addFilter( 
  'editor.BlockListBlock', 
  'advanced-bootstrap-blocks/core/modify-element-edit',
   modifyReusableCoreBlock 
);