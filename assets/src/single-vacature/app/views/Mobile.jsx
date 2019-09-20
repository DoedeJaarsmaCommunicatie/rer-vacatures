import React, { Component } from 'react';
import { Card } from '../../../style/main.css'

export default class Mobile extends Component {
    render() {
        return (
            <Card>
                <div className="title">
                    Mobiele nummer
                </div>

                <div className="content">
                    <a href={'tel:' + this.props.solicitor.phone}>
                        {this.props.solicitor.phone}
                    </a>
                </div>
            </Card>
        )
    }
}
