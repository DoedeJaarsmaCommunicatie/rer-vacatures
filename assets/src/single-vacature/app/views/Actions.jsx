import React, { Component } from 'react';
import { Card, DangerousLink } from '../../../style/main.css'

export default class Actions extends Component {
    render() {
        return (
            <Card>
                <div className="title">
                    Acties
                </div>

                <div className="content">
                    <DangerousLink href={window.delete_link}>
                        Verwijderen
                    </DangerousLink>

                    <a href={window.update_link}>
                        Status aanpassen (momenteel: {this.props.solicitor.status})
                    </a>
                </div>
            </Card>
        )
    }
}
