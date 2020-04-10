const { 
  createHigherOrderComponent 
} = wp.compose;

export const modifyBlockListBlockCardHeader = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == 'advanced-bootstrap-blocks/card-header') {
      props.className = [props.block.attributes.className, "card-header"].join(" ");
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockCardHeader' );

export const modifyGetSaveElementCardHeader = (element, blockType, attributes ) => {
  if (!element) {
    return;
  }
  if (blockType.name == 'advanced-bootstrap-blocks/card-header') {
    return (
      <div className={ [element.props.className, "card-header"].join(" ") }>
        {element}
      </div>
    )
  }
  return element;
}