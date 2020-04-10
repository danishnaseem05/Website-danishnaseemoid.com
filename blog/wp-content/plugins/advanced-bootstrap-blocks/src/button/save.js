import {
  buttonStyle
} from './utils'; 

export const save = (props) => {
  return (
    <a 
      {...props.attributes.anchor ? { id: props.attributes.anchor } : { } }
      className={[props.className, props.attributes.size, buttonStyle(props.attributes), "btn"].join(" ")} 
      href={props.attributes.link} 
      target={props.attributes.newWindow && "_blank"}
      role="button"
      rel={props.attributes.newWindow && 'noopener noreferrer'}
    >
      {props.attributes.text}
    </a>
  );
}