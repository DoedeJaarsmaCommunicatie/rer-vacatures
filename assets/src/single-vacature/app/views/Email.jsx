import React, { Component } from 'react';
import { Card } from '../../../style/main.css'

export default class Email extends Component {
    render() {
        return (
            <Card>
                <div className="title">
                    Email
                </div>

                <div className="content">
                    <a href={'email:' + this.props.solicitor.email}>
                        {this.props.solicitor.email}
                    </a>
                </div>
            </Card>
        )
    }
}
