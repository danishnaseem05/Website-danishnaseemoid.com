const { 
  createHigherOrderComponent 
} = wp.compose;

export const modifyBlockListBlockCardBody = createHigherOrderComponent( ( BlockListBlock ) => {
  return ( props ) => {
    if (props.block.name == 'advanced-bootstrap-blocks/card-body') {
      props.className = [props.block.attributes.className, "card-body"].join(" ");
    }
    return <BlockListBlock { ...props } />;
  };
}, 'modifyBlockListBlockCardBody' );

export const modifyGetSaveElementCardBody = (element, blockType, attributes ) => {
  if (!element) {
    return;
  }
  if (blockType.name == 'advanced-bootstrap-blocks/card-body') {
    return (
      <div className={ [element.props.className, "card-body"].join(" ") }>
        {element}
      </div>
    )
  }
  return element;
}