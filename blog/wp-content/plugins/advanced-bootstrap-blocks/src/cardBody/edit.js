const {
  InnerBlocks
} = wp.blockEditor;

const {
  Fragment
} = wp.element; 

export const edit = (props) => {
  const {
    attributes: {
      TEMPLATE
    },
    className,
    setAttributes
  } = props;
  return (
    <Fragment>
      <InnerBlocks template={TEMPLATE}/>
    </Fragment>
  );
}