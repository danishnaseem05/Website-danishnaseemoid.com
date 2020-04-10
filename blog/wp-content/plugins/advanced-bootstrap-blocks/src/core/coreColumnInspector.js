const { __ } = wp.i18n;
const { Fragment, useEffect, useRef } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow, RangeControl } = wp.components;
const { createHigherOrderComponent, withState } = wp.compose;

const strToRegexColumn = (breakpoint) => {
  const columnBreakpoint = breakpoint.replace('xs', ''); 
  const regexString = `(col){1}[-](breakpoint)[-]?(auto\\b|[0-9][0-9]?\\b)`
    .replace('breakpoint', `${columnBreakpoint}`);
  return new RegExp(regexString); 
}

const strToRegexOffset = (breakpoint) => {
  const offsetBreakpoint = breakpoint.replace('xs', ''); 
  const regexString = `(offset){1}[-](breakpoint)[-]?([0-9][0-9]?\\b)`
    .replace('breakpoint', `${offsetBreakpoint}`);
  return new RegExp(regexString); 
}

const removeColumnClass = (classNameList, breakpoint) => {
  if (typeof classNameList !== "undefined") {
    const regex = strToRegexColumn(breakpoint); 

    return classNameList
      .split(" ")
      .filter(name => { const result = name.match(regex); return !result || result.index !== 0 }); 
  }
}

const removeOffsetClass = (classNameList, breakpoint) => {
  if (typeof classNameList !== "undefined") {
    const regex = strToRegexOffset(breakpoint); 

    return classNameList
      .split(" ")
      .filter(name => { const result = name.match(regex); return !result || result.index !== 0 }); 
  }
}

const returnColumnValue = (props, breakpoint) => {
  if (typeof props.attributes.className !== "undefined") {
    const regex = strToRegexColumn(breakpoint); 
    const results = props.attributes.className.length && props.attributes.className.match(regex) ? Number(props.attributes.className.match(regex)[3].replace("auto", 0)) : -2; 
    if (results > -1) {
      return results;
    }
  } 
  return '';
}

const returnOffsetValue = (props, breakpoint) => {
  if (typeof props.attributes.className !== "undefined") {
    const regex = strToRegexOffset(breakpoint); 
    const results = props.attributes.className.length && props.attributes.className.match(regex) ? Number(props.attributes.className.match(regex)[3]) : -1; 
    if (results > -1) {
      return results;
    }
  } 
  return '';
}

const ColumnControl = withState({
  column: -1,
} )( ({ column, setState, breakpoint, defaultValue, classNameList, setAttributes } ) => {

  useEffect(() => {
    const classNameArray = removeColumnClass(classNameList, breakpoint) || [];
    let classNameListUpdated; 
    if (typeof column !== "undefined" && column.toString().length && column > -1) {
      const newClassNameColumnPrefix = `col-${breakpoint}-`.replace('a','').replace('-xs', '');
      const newClassNameColumnClass = column >= 0 ? Number(column) === 0 ? `${newClassNameColumnPrefix}auto` : `${newClassNameColumnPrefix}${column}` : ''; 
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .concat(newClassNameColumnClass)
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    } else {
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    }
    setAttributes( { 
      className: classNameListUpdated
    });  
  }, [column]); 

  const getColumnValue = (column, defaultValue) => {
    return Number(column) > -1 ? Number(column) : defaultValue; 
  }

  return (
      <RangeControl
        label={ 
          `.col-${breakpoint}-${getColumnValue(column, defaultValue)}`
            .replace('a', '')
            .replace('-xs', '')
            .replace('-0', '-auto')
        }
        value={ getColumnValue(column, defaultValue) }
        allowReset
        onChange={ 
          column => {
            setState({
              column: column
            });
          }
        }
        min={ 0 }
        max={ 12 }
        step={ 1 }
        marks={["auto", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"]}
        separatorType="none"
      />
  );
});

const OffsetControl = withState({
  offset: -1,
} )( ({ offset, setState, breakpoint, defaultValue, classNameList, setAttributes } ) => {

  useEffect(() => {
    const classNameArray = removeOffsetClass(classNameList, breakpoint) || [];
    let classNameListUpdated; 
    if (typeof offset !== "undefined" && offset.toString().length && offset > -2) {
      const newClassNameOffsetPrefix = `offset-${breakpoint}-`.replace('a','').replace('-xs', '');
      const newClassNameOffsetClass = offset >= 0 ? `${newClassNameOffsetPrefix}${offset}` : ''; 
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .concat(newClassNameOffsetClass)
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    } else {
      classNameListUpdated = typeof classNameArray !== "undefined" && classNameArray
        .join(' ')
        .trim()
        .replace(/\s\s+/, ' ');
    }
    setAttributes( { 
      className: classNameListUpdated
    });  
  }, [offset]); 

  const getOffsetValue = (offset, defaultValue) => {
    return Number(offset) > -1 ? Number(offset) : defaultValue; 
  }
  
  return (
      <Fragment>
        <RangeControl
          label={ 
            `.offset-${breakpoint}-${getOffsetValue(offset, defaultValue)}`
              .replace('a', '')
              .replace('-xs', '')
          }
          value={ getOffsetValue(offset, defaultValue) }
          allowReset
          onChange={ 
            offset => {
              setState({
                offset: offset
              });
            }
          }
          min={ 0 }
          max={ 11 }
          step={ 1 }
          marks={["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"]}
          separatorType="none"
        />
      </Fragment>
  );
});

export const CustomColumnInspector = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
    if (props.name.includes("advanced-bootstrap-blocks/column")) {
      const breakpoints = ['xs','sm','md','lg','xl'];
      let columnObject = columnObject || {};

      breakpoints.map( breakpoint => {
        const columnValue = returnColumnValue(props, breakpoint); 
        const offsetValue = returnOffsetValue(props, breakpoint); 
        columnObject[`col-${breakpoint}`] = {
          breakpoint: breakpoint,
          defaultColumnValue: typeof columnValue !== "undefined" ? columnValue : '',
          defaultOffsetValue: typeof offsetValue !== "undefined" ? offsetValue : '',
        }
      });  

      return (
        <Fragment>
          <BlockEdit { ...props } />
          <InspectorControls>
            <PanelBody
              title={ __( 'Column Settings', 'advanced-bootstrap-blocks' ) }
              initialOpen={false}
            >
            {
              props.isSelected && Object.keys(columnObject).map((key, index) => {
                return (
                  <Fragment>
                    <PanelRow>
                      <ColumnControl 
                        breakpoint={ columnObject[key].breakpoint }
                        defaultValue={ columnObject[key].defaultColumnValue }
                        classNameList={ props.attributes.className }
                        setAttributes={ props.setAttributes }
                      />
                    </PanelRow>
                    <PanelRow className="mt-1">
                      <OffsetControl 
                        breakpoint={ columnObject[key].breakpoint }
                        defaultValue={ columnObject[key].defaultOffsetValue }
                        classNameList={ props.attributes.className }
                        setAttributes={ props.setAttributes }
                      />
                    </PanelRow>
                  </Fragment>
                );
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
}, 'CustomColumnInspector' );

