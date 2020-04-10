const {
  InnerBlocks
} = wp.blockEditor;

const {
  Fragment
} = wp.element; 

export const edit = (props) => {
  return (
    <Fragment>
      <InnerBlocks />
    </Fragment>
  );
}