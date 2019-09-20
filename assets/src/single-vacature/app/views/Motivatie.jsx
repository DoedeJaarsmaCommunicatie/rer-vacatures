import React, { Component } from 'react';
import { Card } from '../../../style/main.css'

export default class Motivatie extends Component {
    render() {
        return (
            <Card>
                <div className="title">
                    Motivatie
                </div>

                <div className="content">
                    {this.props.solicitor.motivation}
                </div>
            </Card>
        )
    }
}
