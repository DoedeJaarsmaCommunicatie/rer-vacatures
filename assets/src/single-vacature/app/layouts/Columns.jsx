import React, { Component } from 'react';
import { Columns as Cols} from '../../../style/main.css'

export default class Columns extends Component {
    render() {
        return (
            <Cols>
                {this.props.children}
            </Cols>
        )
    }
}
