const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow } = wp.components;
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;

import {
  customClassNames
} from '../lib';

export const CustomClassNameInspector = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
    if (props.name.includes("advanced-bootstrap-blocks") || props.name.includes("core")) {
      return (
        <Fragment>
          <BlockEdit { ...props } />
          <InspectorControls>     
            <PanelBody
              title={ __( 'Bootstrap Classes', 'advanced-bootstrap-blocks' ) }
              initialOpen={false}
              >
              {
                props.attributes.className && 
                  <PanelRow title="Active Classes" className="my-0 flex-wrap justify-content-start">
                    
                  {
                    props.attributes.className
                    .split(" ")
                    .filter(item => item.trim() != '')
                    .map((item) => {
                      return (
                          <button
                            className="d-inline badge badge-primary border-0 font-weight-light mb-2 mr-2"
                            onClick={() =>  {
                                const classNameList = typeof props.attributes.className !== "undefined" ? props.attributes.className.split(" ") : []; 
                                if (classNameList.includes(item)) {
                                  props.setAttributes( { 
                                    className: classNameList.filter(name => name !== item).join(" ")
                                  } );
                                }
                              } 
                            }
                          >
                            {item} <i className="fa fa-close" aria-hidden="true"></i>
                          </button>
                      )
                    })
                  }
                  </PanelRow>
              }
              <PanelRow>
                <input 
                  id="classNameFilter"
                  type="text"
                  placeholder="Filter class names"
                  className="d-block w-100 mb-3"
                  onChange={e => props.setAttributes({ classNameFilter: e.target.value.replace(".", "") }) }
                />
              </PanelRow>
              
              {
                customClassNames
                  .filter(item => item.className.indexOf(props.attributes.classNameFilter) != -1 ? true : false )
                  .map((item) => {
                    return (
                      <PanelRow className="mt-0">
                        <label
                        >
                          <input 
                            type="checkbox" 
                            checked={
                              typeof props.attributes.className !== "undefined" && 
                              props.attributes.className.split(" ").indexOf(item.className) != -1 ? true : false
                            }
                            onClick={() =>  {
                                const classNameList = typeof props.attributes.className !== "undefined" ? props.attributes.className.split(" ") : []; 
                                if (!classNameList.includes(item.className)) {
                                  props.setAttributes( { 
                                    className: [classNameList.join(" "), item.className].join(" ") 
                                  } );
                                } else {
                                  props.setAttributes( { 
                                    className: classNameList.filter(name => name !== item.className).join(" ")
                                  } );
                                }
                              } 
                            }
                          />
                          <small>{item.className}</small>
                        </label>
                      </PanelRow>
                    )
                  })
              }
            </PanelBody>

          </InspectorControls>
        </Fragment>
      );
    } else {
      return <BlockEdit { ...props } />; 
    }
	};
}, 'CustomClassNameInspector' );